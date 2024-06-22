import pandas as pd
import plotly.express as px
import dash
from dash import dcc, html
from dash.dependencies import Input, Output, State
import json

# Leitura dos ficheiros CSV
pib = pd.read_csv("PIB.csv", delimiter=";", engine='python')
concelho_por_regiao = pd.read_csv("concelhos por região csv.csv", delimiter=";", engine='python')
novas_instalacoes = pd.read_csv("26-centrais.csv", delimiter=";", engine='python')

# Juntar os DataFrames usando a coluna em comum 'Concelho'
dataset1 = novas_instalacoes.merge(concelho_por_regiao, on='Concelho', how='left')
dataset1.dropna(inplace=True)  # Remover linhas com valores NaN

# Juntar os DataFrames usando a coluna em comum 'Ano'
corr_PCxPIB = pib.merge(dataset1, on='Ano', how='left')
corr_PCxPIB.dropna(inplace=True)  # Remover linhas com valores NaN

# Calcular a correlação entre 'Processos Concluidos (#)' e 'PIB a preços constantes' agrupadas por 'Região'
correlacoes = corr_PCxPIB.groupby('Regiao').apply(lambda x: x['Processos Concluidos (#)'].corr(x['PIB a preços constantes'])).reset_index()
correlacoes.columns = ['Região', 'Correlacao_ProcessosConcluidos_PIB']

# Adicionar a coluna de correlação ao DataFrame original
corr_PCxPIB = corr_PCxPIB.merge(correlacoes, left_on='Regiao', right_on='Região', how='left')
corr_PCxPIB.drop(columns=['Região'], inplace=True)

# Exportar para JSON com formatação correta
with open('corr_PCxPIB.json', 'w', encoding='utf-8') as f:
    json.dump(corr_PCxPIB.to_dict(orient='records'), f, ensure_ascii=False, indent=4)

# Função para criar o gráfico dos concelhos
def create_concelhos_graph(region):
    region_data = corr_PCxPIB[corr_PCxPIB['Regiao'] == region]
    concelhos_correlacoes = region_data.groupby('Concelho').apply(lambda x: x['Processos Concluidos (#)'].corr(x['PIB a preços constantes'])).reset_index()
    concelhos_correlacoes.columns = ['Concelho', 'Correlacao_ProcessosConcluidos_PIB']

    fig = px.bar(concelhos_correlacoes, x='Concelho', y='Correlacao_ProcessosConcluidos_PIB', 
                 title=f'Correlações entre Processos Concluídos e PIB - {region}', 
                 labels={'Correlacao_ProcessosConcluidos_PIB': 'Correlação', 'Concelho': 'Concelho'})
    return fig

# Função para criar o gráfico principal das regiões
def create_main_graph():
    fig = px.bar(correlacoes, x='Região', y='Correlacao_ProcessosConcluidos_PIB', 
                 title='Correlações entre Processos Concluídos e PIB por região', 
                 labels={'Correlacao_ProcessosConcluidos_PIB': 'Correlação', 'Região': 'Região'})
    return fig

# Inicializar o aplicativo Dash
app = dash.Dash(__name__)

# Layout do aplicativo
app.layout = html.Div([
    dcc.Graph(id='graph', figure=create_main_graph()),
    html.Button("Voltar", id='back-button', style={'display': 'none'})
])

# Callback para atualizar o gráfico ao clicar em uma barra
@app.callback(
    Output('graph', 'figure'),
    Output('back-button', 'style'),
    Input('graph', 'clickData'),
    Input('back-button', 'n_clicks'),
    State('graph', 'figure')
)
def update_graph(clickData, n_clicks, current_fig):
    ctx = dash.callback_context

    if not ctx.triggered:
        return create_main_graph(), {'display': 'none'}
    else:
        button_id = ctx.triggered[0]['prop_id'].split('.')[0]

        if button_id == 'back-button' and n_clicks > 0:
            return create_main_graph(), {'display': 'none'}
        elif clickData:
            region = clickData['points'][0]['x']
            return create_concelhos_graph(region), {'display': 'block'}
        
    return current_fig, {'display': 'none'}

# Executar o aplicativo
if __name__ == '__main__':
    app.run_server(debug=True)
