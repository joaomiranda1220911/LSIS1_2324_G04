import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
import json

# Leitura dos arquivos CSV
consumo_energia = pd.read_csv("PORDATA_Consumo-de-energia-elétrica-por-tipo-de-consumo.csv", delimiter=";", engine='python')
concelho_por_regiao = pd.read_csv("concelhos por região csv.csv", delimiter=";", engine='python')
total_uni = pd.read_csv("26-centrais.csv", delimiter=";", engine='python')

# Mesclar DataFrames
dataset1 = total_uni.merge(concelho_por_regiao, left_on='Concelho', right_on='Concelho', how='left')
dataset1.dropna(inplace=True)

df_combined = consumo_energia.merge(dataset1, left_on='Concelho', right_on='Concelho', how='left')
df_combined.dropna(inplace=True)

# Calcular correlações
correlacoes = df_combined.groupby('Regiao').apply(lambda x: x['Processos Concluidos (#)'].corr(x['PIB a preços constantes'])).reset_index()
correlacoes.columns = ['Região', 'Correlacao_Número_de_instalacões_Total']

# Mostrar as correlações agrupadas por região
print("\nCorrelações entre Número de instalações e Consumo de Energia por região:")
print(correlacoes)

# Visualização do gráfico de barras
sns.set(style="whitegrid")
plt.figure(figsize=(10, 6))
sns.barplot(x='Região', y='Correlacao_Número_de_instalacões_Total', data=correlacoes)
plt.title('Correlações entre Número de instalações e Consumo de Energia por região')
plt.xlabel('Região')
plt.ylabel('Correlação')
plt.xticks(rotation=45, ha='right')
plt.tight_layout()
plt.show()

# Exportar para JSON
correlacoes_dict = correlacoes.to_dict(orient='records')

with open('corr_CExPC.json', 'w') as f:
    json.dump(correlacoes_dict, f, indent=4)
