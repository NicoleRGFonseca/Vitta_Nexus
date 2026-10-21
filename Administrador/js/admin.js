//cadastra medicos
const doctorForm = document.getElementById("doctorForm");

doctorForm.addEventListener("submit", async function(event) {
    event.preventDefault();

    const dadosFormulario = new FormData(doctorForm);

    try {
        const resposta = await fetch("salvar_medico.php", {
            method: "POST",
            body: dadosFormulario
        });

        const dados = await resposta.json();

        showToast(dados.mensagem);

        if (dados.sucesso) {
            doctorForm.reset();
        }

    } catch (erro) {
        console.error("Erro ao cadastrar médico:", erro);
        showToast("Erro ao enviar os dados para o servidor.");
    }
});
