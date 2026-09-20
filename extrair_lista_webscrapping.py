# %%
import re
import os
import random
import time

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.firefox.options import Options as FirefoxOptions
from selenium.webdriver.edge.options import Options as EdgeOptions
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select
from selenium.common.exceptions import NoSuchElementException, TimeoutException

import pandas as pd
import numpy as np
import streamlit as st

#1° ETAPA (DISPARAR CLIENTES NA LISTA);
#2° ETAPA (ATENDER CLIENTES NA FILA AUTOMATICAMENTE)
#3° ETAPA (CAPTURAR INDICADORES DOS ATENDIMENTOS DURANTE A AUTOMAÇÃO)


# %%
def get_driver(browser=None):

    if browser == "chrome":
        return webdriver.Chrome()

    elif browser == "firefox":
        return webdriver.Firefox()

    elif browser == "edge":
        return webdriver.Edge()

    elif browser == "brave":
        options = Options()
        options.binary_location = r"C:\Program Files\BraveSoftware\Brave-Browser\Application\brave.exe"
        return webdriver.Chrome(options=options)

    else:
        raise ValueError("Sem driver no selenium")


driver = get_driver("brave")  
driver.get("https://prd-sac.onrender.com/sistema/lista_atendimentos.php")

wait = WebDriverWait(driver, 0.5)

# %%
#Faz a captura dos atendimentos atuais na lista, i
def extrair_protocolos(driver):

    df_protocolos = []

    tr = wait.until(EC.presence_of_all_elements_located((By.XPATH, '//table/tbody/tr')))
    trs = len(tr)

    for i in range(1, trs + 1):

        col = driver.find_elements(By.XPATH, f'//table/tbody/tr[{i}]/td')
        aberto = ""

        if len(col) >= 8:


            status_text = col[5].text.strip()
            print(f"{i}° ATENDIMENTO - PROTOCOLO: {col[0].text.strip()}" )
            print(status_text)

            try:
                botao = wait.until(EC.element_to_be_clickable((By.XPATH, f'/html/body/div/table/tbody/tr[{i}]/td[8]/a/button')))      
                aberto = botao.text.strip()
            except (NoSuchElementException, TimeoutException):
                pass

            status_aberto = "Em aberto" if "Assumir" in aberto else ("Em atendimento" if "Chat" in aberto else "Encerrado") 
            status_concluido = "Sim" if "CONCLUÍDO" in status_text else "Não"
            status_expirado = "Sim" if "EXPIRADO" in status_text else "Não"
            status_cancelado = "Sim" if "CANCELADO" in status_text else "Não"

            df_protocolos.append({
            "protocolo": col[0].text.strip(),
            "servico": col[2].text.strip(),
            "tipo_servico": col[3].text.strip(),
            "validade": col[4].text.strip(),
            "feedback": col[6].text.strip(),
            "abertos": status_aberto,
            "concluidos": status_concluido,
            "expirados": status_expirado,
            "cancelados": status_cancelado
            })

            print("=" * 50)
            print(f"PROTOCOLO:  {df_protocolos[-1]}")
            print("="*50)

             
    df = pd.DataFrame(df_protocolos)
    return df

extrair_protocolos(driver)

# %%
# def relatorio_de_atendimentos(df):

#     df_relatorio = pd.DataFrame(df)
#     total_abertos = df_relatorio['abertos'].value_counts()['Em aberto']
#     total_concluído = df_relatorio['concluidos'].value_counts()['Sim']
#     total_expirados = df_relatorio['expirados'].value_counts()['Sim']
#     total_cancelados = df_relatorio['cancelados'].value_counts()['Sim']
#     media_feedback = df_relatorio['feedback'].mean()
#     servico_em_alta = df_relatorio['servico'].value_counts().max()

    


    



