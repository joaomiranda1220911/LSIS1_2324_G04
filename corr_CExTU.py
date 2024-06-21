import csv
import numpy
import matplotlib.pyplot as plt
import seaborn as sns
import json

# Função para ler arquivo CSV e retornar os dados como uma lista de dicionários
def ler_csv(nome_arquivo):
    dados = []
    with open(nome_arquivo, 'r', encoding='utf-8') as csvfile:
        reader = csv.DictReader(csvfile, delimiter=';')
        for row in reader:
            dados.append(row)
    return dados

# Leitura dos arquivos CSV
consumo_energia = ler_csv("PORDATA_Consumo-de-energia-elétrica-por-tipo-de-consumo.csv")
concelho_por_regiao = ler_csv("concelhos por região csv.csv")
total_uni = ler_csv("8-unidades-de-producao-para-autoconsumo.csv")

# Criar um dicionário de regiões e concelhos para facilitar a mesclagem
regiao_concelho = {item['Concelho']: item['Regiao'] for item in concelho_por_regiao}

# Mesclar total_uni com concelho_por_regiao usando a coluna 'Concelho'
dataset1 = []
for item in total_uni:
    if item['Concelho'] in regiao_concelho:
        item['Regiao'] = regiao_concelho[item['Concelho']]
        dataset1.append(item)

# Mesclar consumo_energia com dataset1 usando a coluna 'Concelho'
df_combined = []
for item in consumo_energia:
    for unit in dataset1:
        if item['Concelho'] == unit['Concelho']:
            item.update(unit)  # Atualiza o dicionário com as informações de unit
            df_combined.append(item)
            break  # Para evitar duplicatas se houver múltiplas correspondências

# Filtrar linhas sem valores NaN
df_combined = [item for item in df_combined if all(item.values())]

# Calcular a correlação entre 'Número de instalações' e 'Total' agrupadas por 'Região'
correlacoes = {}
for item in df_combined:
    regiao = item['Regiao']
    if regiao not in correlacoes:
        correlacoes[regiao] = {'Numero_instalacoes': [], 'Total': []}
    correlacoes[regiao]['Numero_instalacoes'].append(float(item['Número de instalações']))
    correlacoes[regiao]['Total'].append(float(item['Total']))

# Calcular as correlações e armazenar em um novo dicionário
correlacoes_resultado = {}
for regiao, valores in correlacoes.items():
    correlacao = numpy.corrcoef(valores['Numero_instalacoes'], valores['Total'])[0, 1]
    correlacoes_resultado[regiao] = correlacao

# Mostrar as correlações por região
for regiao, correlacao in correlacoes_resultado.items():
    print(f"Região: {regiao}, Correlação: {correlacao}")

# Exportar as correlações para um arquivo JSON
with open('corr_CExTU.json', 'w', encoding='utf-8') as f:
    json.dump(correlacoes_resultado, f, ensure_ascii=False, indent=4)


# Visualização das correlações em um gráfico de barras
correlacoes_df = [{'Região': regiao, 'Correlacao': correlacao} for regiao, correlacao in correlacoes_resultado.items()]
sns.set(style="whitegrid")
plt.figure(figsize=(10, 6))
sns.barplot(x='Região', y='Correlacao', data=correlacoes_df)
plt.title('Correlações entre Número de instalações e Consumo de Energia por região')
plt.xlabel('Região')
plt.ylabel('Correlação')
plt.xticks(rotation=45, ha='right')
plt.tight_layout()
plt.show()
