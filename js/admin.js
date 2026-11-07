
document.addEventListener("DOMContentLoaded", function () {
    const doctorForm = document.getElementById("doctorForm");

    // Abrir modal de horários
const openScheduleBtn = document.getElementById("openScheduleModal");
const scheduleModal = document.getElementById("scheduleModal");
const scheduleForm = document.getElementById("scheduleForm");

if (openScheduleBtn && scheduleModal) {
    openScheduleBtn.addEventListener("click", () => {
        scheduleModal.classList.add("show");
    });
}

// Submissão do formulário de horários via Fetch
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
                
                // Recarrega em 1.5s para mostrar a tabela atualizada
                setTimeout(() => location.reload(), 1500);
            }
        } catch (erro) {
            console.error("Erro ao salvar horário:", erro);
            showToast("Erro de comunicação com o servidor.");
        }
    });
}

    if (!doctorForm) {
        return;
        
    }

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
            }

        } catch (erro) {
            console.error("Erro ao cadastrar médico:", erro);
            showToast("Erro ao enviar os dados para o servidor.");
        }
    });
});
