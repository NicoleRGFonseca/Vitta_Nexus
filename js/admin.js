document.addEventListener("DOMContentLoaded", function () {

    // =====================================================
    // CADASTRO DE HORÁRIOS
    // =====================================================
    const openScheduleBtn = document.getElementById("openScheduleModal");
    const scheduleModal = document.getElementById("scheduleModal");
    const scheduleForm = document.getElementById("scheduleForm");

    if (openScheduleBtn && scheduleModal) {
        openScheduleBtn.addEventListener("click", () => {
            scheduleModal.classList.add("show");
        });
    }

    if (scheduleForm) {
        scheduleForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const formData = new FormData(scheduleForm);

            try {
                const resposta = await fetch("control/salvar_horario.php", {
                    method: "POST",
                    body: formData
                });

                const dados = await resposta.json();

                showToast(dados.mensagem);

                if (dados.sucesso) {
                    scheduleForm.reset();
                    scheduleModal.classList.remove("show");
                    
                    setTimeout(() => location.reload(), 1500);
                }
            } catch (erro) {
                console.error("Erro ao salvar horário:", erro);
                showToast("Erro de comunicação com o servidor.");
            }
        });
    }


    // =====================================================
    // CADASTRO DE MÉDICOS (DINÂMICO SEM RECARREGAR)
    // =====================================================
    const doctorForm = document.getElementById("doctorForm");

    if (doctorForm) {
        doctorForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const dadosFormulario = new FormData(doctorForm);

            try {
                const resposta = await fetch("control/salvar_medico.php", {
                    method: "POST",
                    body: dadosFormulario
                });

                if (!resposta.ok) {
                    throw new Error("Erro na resposta do servidor.");
                }

                const dados = await resposta.json();

                showToast(dados.mensagem);

                if (dados.sucesso) {
                    doctorForm.reset();

                    const doctorModal = document.getElementById("doctorModal");
                    if (doctorModal) {
                        doctorModal.classList.remove("show");
                    }

                    // Cria o card do médico dinamicamente na tela sem recarregar a página
                    const doctorGrid = document.querySelector(".doctor-grid");
                    
                    if (doctorGrid) {
                        const emptyMsg = doctorGrid.querySelector("p");
                        if (emptyMsg && emptyMsg.textContent.includes("Nenhum médico")) {
                            emptyMsg.remove();
                        }

                        const m = dados.medico;
                        const iniciais = m.nome.substring(0, 2).toUpperCase();

                        const newCard = document.createElement("article");
                        newCard.className = "doctor-card";
                        
                        newCard.innerHTML = `
                            <div class="doctor-card-top">
                                <div class="doctor-avatar">${iniciais}</div>
                                <span class="active-pill">Ativo</span>
                            </div>
                            <h3>${m.nome}</h3>
                            <p>Especialidade: ${m.nome_especialidade}</p>
                            <div class="doctor-details">
                                <span>CRM: ${m.crm}</span>
                                <span>${m.email}</span>
                            </div>
                            <div class="doctor-actions">
                                <button type="button" class="outline-button small-button">Editar</button>
                                <button type="button" class="ghost-button" aria-label="Mais opções">•••</button>
                            </div>
                        `;

                        doctorGrid.appendChild(newCard);
                    }
                }

            } catch (erro) {
                console.error("Erro ao cadastrar médico:", erro);
                showToast("Erro ao enviar os dados para o servidor.");
            }
        });
    }

});