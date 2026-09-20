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
driver.get("https://prd-sac.onrender.com/sistema/solicitar_atendimento.php")
wait = WebDriverWait(driver, 10)

# %%
cliente = [
    {"nome": "Lucas Silva", "cpf": "53920184726", "data": "01-09-2026"},
    {"nome": "Ana Souza", "cpf": "10485937261", "data": "01-09-2026"},
    {"nome": "Bruno Oliveira", "cpf": "84729103658", "data": "01-09-2026"},
    {"nome": "Beatriz Santos", "cpf": "29581047213", "data": "01-09-2026"},
    {"nome": "Gabriel Rodrigues", "cpf": "71048293654", "data": "01-09-2026"},
    {"nome": "Carla Ferreira", "cpf": "48295107386", "data": "02-09-2026"},
    {"nome": "Matheus Alves", "cpf": "93018472516", "data": "02-09-2026"},
    {"nome": "Fernanda Pereira", "cpf": "07456931842", "data": "02-09-2026"},
    {"nome": "Pedro Lima", "cpf": "33919576420", "data": "02-09-2026"},
    {"nome": "Mariana Gomes", "cpf": "19283746502", "data": "02-09-2026"},
    {"nome": "Guilherme Costa", "cpf": "29384756013", "data": "03-09-2026"},
    {"nome": "Camila Ribeiro", "cpf": "39485760124", "data": "03-09-2026"},
    {"nome": "Gustavo Martins", "cpf": "49586701235", "data": "03-09-2026"},
    {"nome": "Juliana Carvalho", "cpf": "59687012346", "data": "03-09-2026"},
    {"nome": "Felipe Almeida", "cpf": "69780123457", "data": "03-09-2026"},
    {"nome": "Patricia Lopes", "cpf": "79801234568", "data": "04-09-2026"},
    {"nome": "João Soares", "cpf": "89012345679", "data": "04-09-2026"},
    {"nome": "Aline Fernandes", "cpf": "90123456780", "data": "04-09-2026"},
    {"nome": "Enzo Vieira", "cpf": "14253647586", "data": "04-09-2026"},
    {"nome": "Vanessa Barbosa", "cpf": "25364758697", "data": "04-09-2026"},
    {"nome": "Rafael Rocha", "cpf": "36475869708", "data": "05-09-2026"},
    {"nome": "Larissa Dias", "cpf": "47586970819", "data": "05-09-2026"},
    {"nome": "Thiago Nascimento", "cpf": "58697081920", "data": "05-09-2026"},
    {"nome": "Amanda Andrade", "cpf": "69708192031", "data": "05-09-2026"},
    {"nome": "Leonardo Moreira", "cpf": "70819203142", "data": "05-09-2026"},
    {"nome": "Leticia Nunes", "cpf": "81920314253", "data": "06-09-2026"},
    {"nome": "Rodrigo Marques", "cpf": "92031425364", "data": "06-09-2026"},
    {"nome": "Jessica Machado", "cpf": "03142536475", "data": "06-09-2026"},
    {"nome": "Vinicius Mendes", "cpf": "15263748596", "data": "06-09-2026"},
    {"nome": "Carolina Freitas", "cpf": "26374859607", "data": "06-09-2026"},
    {"nome": "Eduardo Cardoso", "cpf": "37485960718", "data": "07-09-2026"},
    {"nome": "Bruna Ramos", "cpf": "48596071829", "data": "07-09-2026"},
    {"nome": "Diego Gonçalves", "cpf": "59607182930", "data": "07-09-2026"},
    {"nome": "Stephanie Santana", "cpf": "60718293041", "data": "07-09-2026"},
    {"nome": "Carlos Teixeira", "cpf": "71829304152", "data": "07-09-2026"},
    {"nome": "Nicole Castro", "cpf": "82930415263", "data": "08-09-2026"},
    {"nome": "André Correia", "cpf": "93041526374", "data": "08-09-2026"},
    {"nome": "Bianca Duarte", "cpf": "04152637485", "data": "08-09-2026"},
    {"nome": "Victor Melo", "cpf": "16273849506", "data": "08-09-2026"},
    {"nome": "Natalia Monteiro", "cpf": "27384950617", "data": "08-09-2026"},
    {"nome": "Marcelo Farias", "cpf": "38495061728", "data": "09-09-2026"},
    {"nome": "Paula Ribeiro", "cpf": "49506172839", "data": "09-09-2026"},
    {"nome": "Daniel Moura", "cpf": "50617283940", "data": "09-09-2026"},
    {"nome": "Renata Cavalcante", "cpf": "61728394051", "data": "09-09-2026"},
    {"nome": "Caio Dias", "cpf": "72839405162", "data": "09-09-2026"},
    {"nome": "Tatiana Barros", "cpf": "83940516273", "data": "10-09-2026"},
    {"nome": "Samuel Freire", "cpf": "94051627384", "data": "10-09-2026"},
    {"nome": "Debora Fernandes", "cpf": "05162738495", "data": "10-09-2026"},
    {"nome": "Lucca Araujo", "cpf": "17283940516", "data": "10-09-2026"},
    {"nome": "Raquel Viana", "cpf": "28394051627", "data": "10-09-2026"},
    {"nome": "Murilo Campos", "cpf": "39405162738", "data": "11-09-2026"},
    {"nome": "Monalisa Silveira", "cpf": "40516273849", "data": "11-09-2026"},
    {"nome": "Arthur Medeiros", "cpf": "51627384950", "data": "11-09-2026"},
    {"nome": "Clarice Cunha", "cpf": "62738495061", "data": "11-09-2026"},
    {"nome": "Bernardo Rocha", "cpf": "73849506172", "data": "11-09-2026"},
    {"nome": "Rebeca Guimarães", "cpf": "84950617283", "data": "12-09-2026"},
    {"nome": "Heitor Rezende", "cpf": "95061728394", "data": "12-09-2026"},
    {"nome": "Sabrina Borges", "cpf": "06172839405", "data": "12-09-2026"},
    {"nome": "Miguel Nogueira", "cpf": "18293041526", "data": "12-09-2026"},
    {"nome": "Isabela Pires", "cpf": "29304152637", "data": "12-09-2026"},
    {"nome": "Davi Machado", "cpf": "30415263748", "data": "13-09-2026"},
    {"nome": "Luana Siqueira", "cpf": "41526374859", "data": "13-09-2026"},
    {"nome": "Lorenzo Castro", "cpf": "52637485901", "data": "13-09-2026"},
    {"nome": "Gisele Carvalho", "cpf": "63748590112", "data": "13-09-2026"},
    {"nome": "Theo Aguiar", "cpf": "74859011223", "data": "13-09-2026"},
    {"nome": "Vanessa Ramos", "cpf": "85901122334", "data": "14-09-2026"},
    {"nome": "Benjamin Pinto", "cpf": "96011223345", "data": "14-09-2026"},
    {"nome": "Priscila Farias", "cpf": "07112233456", "data": "14-09-2026"},
    {"nome": "Nicolas Freitas", "cpf": "19223344567", "data": "14-09-2026"},
    {"nome": "Livia Correia", "cpf": "20334455678", "data": "14-09-2026"},
    {"nome": "Joaquim Peixoto", "cpf": "31445566789", "data": "15-09-2026"},
    {"nome": "Renan Silveira", "cpf": "42556677890", "data": "15-09-2026"},
    {"nome": "Julia Fontes", "cpf": "53667788901", "data": "15-09-2026"},
    {"nome": "Igor Antunes", "cpf": "64778899012", "data": "15-09-2026"},
    {"nome": "Sophia Franco", "cpf": "75889900123", "data": "15-09-2026"},
    {"nome": "Alexandre Meireles", "cpf": "86990011234", "data": "16-09-2026"},
    {"nome": "Isabella Moraes", "cpf": "97001122345", "data": "16-09-2026"},
    {"nome": "Vitor Bezerra", "cpf": "08112233456", "data": "16-09-2026"},
    {"nome": "Manuela Paiva", "cpf": "19223344568", "data": "16-09-2026"},
    {"nome": "Fabio Guimarães", "cpf": "20334455679", "data": "16-09-2026"},
    {"nome": "Giovanna Rezende", "cpf": "31445566780", "data": "17-09-2026"},
    {"nome": "Samuel Barreto", "cpf": "42556677891", "data": "17-09-2026"},
    {"nome": "Alice Xavier", "cpf": "53667788902", "data": "17-09-2026"},
    {"nome": "Otavio Brandão", "cpf": "64778899013", "data": "17-09-2026"},
    {"nome": "Laura Caldeira", "cpf": "75889900124", "data": "17-09-2026"},
    {"nome": "Hugo Matos", "cpf": "86990011235", "data": "18-09-2026"},
    {"nome": "Luiza Telles", "cpf": "97001122346", "data": "18-09-2026"},
    {"nome": "Caio Neves", "cpf": "08112233457", "data": "18-09-2026"},
    {"nome": "Maria Tavares", "cpf": "19223344569", "data": "18-09-2026"},
    {"nome": "Douglas Prado", "cpf": "20334455680", "data": "18-09-2026"},
    {"nome": "Rafaela Sampaio", "cpf": "31445566781", "data": "19-09-2026"},
    {"nome": "Danilo Sales", "cpf": "42556677892", "data": "19-09-2026"},
    {"nome": "Gabriela Cordeiro", "cpf": "53667788903", "data": "19-09-2026"},
    {"nome": "Adriano Godoy", "cpf": "64778899014", "data": "19-09-2026"},
    {"nome": "Thais Fonseca", "cpf": "75889900125", "data": "19-09-2026"},
    {"nome": "Lucas Silva", "cpf": "86990011236", "data": "20-09-2026"},
    {"nome": "Ana Souza", "cpf": "97001122347", "data": "20-09-2026"},
    {"nome": "Bruno Oliveira", "cpf": "08112233458", "data": "20-09-2026"},
    {"nome": "Beatriz Santos", "cpf": "19223344570", "data": "20-09-2026"},
    {"nome": "Gabriel Rodrigues", "cpf": "20334455681", "data": "20-09-2026"}
]

# %%
def disparar_atendimento(driver):
    
    for clientes in cliente:
        wait = WebDriverWait(driver, 10)
        nome = wait.until(EC.presence_of_element_located((By.NAME, "nome")))
        nome.clear()
        nome.send_keys(clientes["nome"])
        
        cpf = wait.until(EC.presence_of_element_located((By.NAME, "cpf")))
        cpf.clear()
        cpf.send_keys(clientes["cpf"])
        
        data_nasc = wait.until(EC.presence_of_element_located((By.XPATH, '/html/body/div[1]/form/div[3]/input')))
        data_nasc.clear()
        data_nasc.send_keys(clientes["data"])

        id_serv_element = wait.until(EC.presence_of_element_located((By.NAME, "id_serv")))
        id_serv = Select(id_serv_element)
        if id_serv.options[1:]:
            opcao = random.choice(id_serv.options[1:])
            opcao.click()

        solicitar = wait.until(EC.element_to_be_clickable((By.XPATH, '/html/body/div[1]/form/button')))
        time.sleep(2)
        solicitar.click()
        
        driver.back()

    print("Disparo efetuado")
    driver.quit()

disparar_atendimento(driver)


