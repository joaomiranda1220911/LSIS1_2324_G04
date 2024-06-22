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

# Calcular a correlação entre 'Processos Concluidos (#)' e 'PIB a preços constantes' agrupadas por 'Concelho'
correlacoes = corr_PCxPIB.groupby(['Regiao', 'Concelho']).apply(lambda x: x['Processos Concluidos (#)'].corr(x['PIB a preços constantes'])).reset_index()
correlacoes.columns = ['Regiao', 'Concelho', 'Correlacao_ProcessosConcluidos_PIB']

# Preparar o JSON com os dados de correlação por concelho, removendo NaN
json_data = []
for regiao, concelho, correlacao in zip(correlacoes['Regiao'], correlacoes['Concelho'], correlacoes['Correlacao_ProcessosConcluidos_PIB']):
    # Verificar se a correlação é um valor numérico válido antes de adicionar ao JSON
    if pd.notna(correlacao):  # Verifica se não é NaN
        json_data.append({
            'Regiao': regiao,
            'Concelho': concelho,
            'Correlacao_ProcessosConcluidos_PIB': float(correlacao)  # Garantir que a correlação seja convertida para float
        })

# Exportar para o arquivo JSON
with open('corr_PCxPIB.json', 'w', encoding='utf-8') as f:
    json.dump(json_data, f, ensure_ascii=False, indent=4)

# Função para criar o gráfico dos concelhos
def create_concelhos_graph(region):
    region_data = corr_PCxPIB[corr_PCxPIB['Regiao'] == region]

    fig = px.bar(region_data, x='Concelho', y='Correlacao_ProcessosConcluidos_PIB', 
                 title=f'Correlações entre Processos Concluídos e PIB - {region}', 
                 labels={'Correlacao_ProcessosConcluidos_PIB': 'Correlação', 'Concelho': 'Concelho'})
    return fig

# Função para criar o gráfico principal das regiões
def create_main_graph():
    fig = px.bar(correlacoes, x='Regiao', y='Correlacao_ProcessosConcluidos_PIB', 
                 title='Correlações entre Processos Concluídos e PIB por região', 
                 labels={'Correlacao_ProcessosConcluidos_PIB': 'Correlação', 'Regiao': 'Região'})
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
