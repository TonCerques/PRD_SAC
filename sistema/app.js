document.addEventListener("DOMContentLoaded", function() {
    console.log("Sistema PRD_SAC carregado.");

    // ID do botão é modificado dinamicamente via JS após 2 segundos
    setTimeout(function() {
        let btn = document.getElementById("btn_001a_sub");
        if(btn) {
            btn.setAttribute("id", "btn_001a_sub_MODIFIED_" + Math.floor(Math.random() * 1000));
            btn.setAttribute("data-selenium-target", "submit-atendimento-action");
        }
    }, 2000);
});

function loadData(id) {
    let protVal = document.getElementById("prt_val_" + id).innerText;
    
    // Criação de modal dinâmico com IDs aleatórios
    let modalId = "mdl_dyn_" + Math.floor(Math.random() * 500);
    let modal = document.createElement("div");
    modal.setAttribute("id", modalId);
    modal.setAttribute("class", "custom-modal-overlay");
    modal.innerHTML = `
        <div class="modal-content" id="inner_mdl_box">
            <h3>Detalhes do Atendimento</h3>
            <p id="txt_prot_out">Protocolo: <strong>${protVal}</strong></p>
            <button id="btn_close_mdl_99" onclick="document.getElementById('${modalId}').remove()">Fechar</button>
        </div>
    `;
    document.body.appendChild(modal);
}