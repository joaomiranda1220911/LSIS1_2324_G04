import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.linear_model import LinearRegression
from sklearn.metrics import r2_score
import json

# Caminho dos arquivos CSV
file_path_instalacoes = "8-unidades-de-producao-para-autoconsumo.csv"
file_path_education = "PORDATA_População-por-nível-de-escolaridade-segundo-os-Censos-(percentagem).csv"

# Carregar os dados de instalações por concelho
total_uni = pd.read_csv(file_path_instalacoes, delimiter=';', engine='python')
total_uni = total_uni[['Concelho', 'Número de instalacões']]

# Carregar os dados de educação por concelho
df_education = pd.read_csv(file_path_education, delimiter=';')

# Verificar e tratar valores NaN na coluna 'Âmbito Geográfico'
df_education['Âmbito Geográfico'] = df_education['Âmbito Geográfico'].fillna('')

# Garantir que a coluna "Âmbito Geográfico" é de tipo string
df_education['Âmbito Geográfico'] = df_education['Âmbito Geográfico'].astype(str)

# Remover as linhas que começam com "NUTS"
df_education = df_education[~df_education['Âmbito Geográfico'].str.contains('NUTS')]

# Selecionar as colunas relevantes para educação
cols_to_select = ['Concelho', 'Sem nível de escolaridade', 'Básico 1º ciclo', 'Básico 2º ciclo',
                  'Básico 3º ciclo', 'Secundário', 'Médio', 'Superior']

# Filtrar o DataFrame para incluir apenas as colunas selecionadas
df_education = df_education[cols_to_select]

# Remover caracteres estranhos e valores inválidos nas colunas numéricas
for col in cols_to_select[1:]:
    df_education[col] = df_education[col].str.replace('-', '0').str.replace(',', '.').str.strip()
    df_education[col] = pd.to_numeric(df_education[col], errors='coerce')

# Remover registros com valores inválidos
df_education = df_education.dropna()

# Mesclar os dois datasets com base nos concelhos/municípios
df_merged = pd.merge(total_uni, df_education, on='Concelho', how='inner')

# Calcular a matriz de correlação entre 'Número de instalações' e as variáveis de educação
correlation_matrix = df_merged[['Número de instalacões'] + cols_to_select[1:]].corr()

print("\nMatriz de correlação:")
print(correlation_matrix['Número de instalacões'])

# Preparar os dados para o gráfico de barras das correlações
correlacoes = pd.DataFrame({
    'Variável de Educação': cols_to_select[1:],  # Excluir 'Concelho' da lista de variáveis
    'Correlação': correlation_matrix.loc['Número de instalacões', cols_to_select[1:]].values
})

# Definir o estilo do gráfico (opcional)
sns.set(style="whitegrid")

# Criar o gráfico de barras das correlações
plt.figure(figsize=(10, 6))
sns.barplot(x='Variável de Educação', y='Correlação', data=correlacoes, palette='viridis')
plt.title('Correlações entre Número de Instalações e Variáveis de Educação')
plt.xlabel('Variável de Educação')
plt.ylabel('Correlação')
plt.xticks(rotation=45, ha='right')
plt.tight_layout()
plt.show()

# Selecionar uma variável de educação para a regressão linear
variavel_educacao = 'Superior'  # Exemplo: 'Superior', 'Secundário', etc.

# Preparar os dados para a regressão linear
X = df_merged[[variavel_educacao]].values
y = df_merged['Número de instalacões'].values

# Ajustar o modelo de regressão linear
model = LinearRegression()
model.fit(X, y)

# Fazer previsões usando o modelo
y_pred = model.predict(X)

# Calcular o coeficiente de determinação (R²)
r2 = r2_score(y, y_pred)

print(f"\nRegressão Linear entre Número de Instalações e {variavel_educacao}:")
print(f"Coeficiente de determinação (R²): {r2}")

# Plotar o gráfico de dispersão com a linha de regressão
plt.figure(figsize=(10, 6))
plt.scatter(X, y, color='blue', alpha=0.6, label='Dados reais')
plt.plot(X, y_pred, color='red', label='Linha de Regressão')
plt.title(f'Regressão Linear entre Número de Instalações e {variavel_educacao}')
plt.xlabel(variavel_educacao)
plt.ylabel('Número de Instalações')
plt.legend()
plt.grid(True)
plt.show()

# Criar um dicionário com as correlações
corr_dict = correlation_matrix['Número de instalacões'].drop('Número de instalacões').to_dict()

# Salvar o dicionário como um ficheiro JSON
with open('corr_NExTU.json', 'w') as json_file:
    json.dump(corr_dict, json_file)

print("\nCorrelação guardada em 'corr_NExTU.json'")
