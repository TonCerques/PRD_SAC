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

wait = WebDriverWait(driver, 10)

# %%
def iniciar_atendimento(aba_lista):

    print(driver.current_url)
    print("Atendimento CHAT - Iniciado")
    for i in range(2):      

        select = wait.until(EC.presence_of_element_located((By.NAME, 'resposta_atendente')))  
        select_chat = Select(select)
    
        if select_chat.options[1:]:
            opcao = random.choice(select_chat.options[1:])     
            opcao.click()

        botao = wait.until(EC.element_to_be_clickable((By.XPATH, '/html/body/div[1]/form/button')))
        botao.click()

    driver.close()

# %%
#simulação de um atendimento real, pra permitir as expirações, cancelamentos, aberturas e atendimentos
def abrir_atendimento():

    tr = wait.until(EC.presence_of_all_elements_located((By.XPATH, '//table/tbody/tr')))
    trs = len(tr)
    protocolados = []
    url = "https://prd-sac.onrender.com/sistema/lista_atendimentos.php"
    
    for i in range(1, trs + 1):
        try:
            col = driver.find_elements(By.XPATH, f'//table/tbody/tr[{i}]/td')

            if len(col) >= 8:
                status_text = col[6].text.strip()
                protocolo = col[0].text.strip()

                if status_text == "-":
                    if protocolo in protocolados:
                            print("Processo já finalizado, segue para o próximo")
                            continue
                    else:
                        botao = wait.until(EC.element_to_be_clickable((By.XPATH, f'/html/body/div/table/tbody/tr[{i}]/td[8]/a/button'))) 
                        aba_lista = driver.current_window_handle
                        botao.click()
                        driver.switch_to.window(driver.window_handles[-1])

                        #Segurança pra falha de delay do site hospedado
                        aba_atual = driver.current_url
                        while aba_atual in url:
                            driver.refresh()
                            botao = wait.until(EC.element_to_be_clickable((By.XPATH, f'/html/body/div/table/tbody/tr[{i}]/td[8]/a/button'))) 
                            aba_lista = driver.current_window_handle
                            botao.click()
                            driver.switch_to.window(driver.window_handles[-1])
                            break
                            
                        
                        iniciar_atendimento(aba_lista)
                        driver.switch_to.window(aba_lista)
                        time.sleep(2)
                        driver.refresh()
                        print("Atendimento CHAT - Finalizado")  
                        protocolados.append(protocolo)
                        wait.until(EC.presence_of_all_elements_located((By.XPATH, '//table/tbody/tr')))
                        time.sleep(2)

        except TimeoutException:
            print("Fim dos atendimentos disponíveis")
            break
                    

abrir_atendimento()       


