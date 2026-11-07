<?php
require_once '../config/conexao.php';

// 1. Buscar especialidades cadastradas no banco de dados
$stmtEsp = $pdo->query("SELECT nome FROM especialidades ORDER BY nome ASC");
$especialidades = $stmtEsp->fetchAll(PDO::FETCH_COLUMN);

// 2. Mapeamento de dias da semana em inglês para português
$diasMap = [
    'Sunday' => 'Domingo',
    'Monday' => 'Segunda-feira',
    'Tuesday' => 'Terça-feira',
    'Wednesday' => 'Quarta-feira',
    'Thursday' => 'Quinta-feira',
    'Friday' => 'Sexta-feira',
    'Saturday' => 'Sábado'
];

$dias = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira'];

// 3. Buscar horários disponíveis na tabela horarios_disponiveis vinculados aos médicos e especialidades[cite: 30]
$sqlHorarios = "SELECT h.id_horario, h.data_hora_inicio, e.nome AS especialidade 
                FROM horarios_disponiveis h
                JOIN medicos m ON h.id_medico = m.id_medico
                JOIN especialidades e ON m.id_esp = e.id_esp
                WHERE h.status = 'disponivel' AND h.data_hora_inicio >= NOW()
                ORDER BY h.data_hora_inicio ASC";

$stmtHorarios = $pdo->query($sqlHorarios);
$todosHorarios = $stmtHorarios->fetchAll(PDO::FETCH_ASSOC);

// Organizar no formato: [Especialidade][Dia da Semana] = [ { id: id_horario, hora: 'HH:MM' }, ... ][cite: 30]
$disponibilidade = [];
foreach ($todosHorarios as $h) {
    $esp = $h['especialidade'];
    $timestamp = strtotime($h['data_hora_inicio']);
    $diaIngles = date('l', $timestamp);
    $diaPt = $diasMap[$diaIngles] ?? $diaIngles;
    
    $hora = date('H:i', $timestamp);
    
    // Guardamos o id_horario do banco junto com a hora formatada[cite: 30]
    $disponibilidade[$esp][$diaPt][] = [
        'id' => $h['id_horario'],
        'hora' => $hora
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitta Nexus | Agendar consulta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Quicksand:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .patient-content { padding: 32px 38px; }
        .patient-intro { max-width: 760px; }
        .patient-notice { margin: 20px 0; padding: 13px 16px; border: 1px solid #99e4d7; border-radius: 10px; background: var(--primary-light); color: var(--primary-dark); line-height: 1.55; font-size: 12px; }
        .booking-layout { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(250px, .75fr); gap: 22px; align-items: start; }
        .booking-panel, .summary-panel { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); }
        .booking-panel { padding: 26px; }
        .booking-panel h2, .summary-panel h2 { margin: 5px 0 8px; }
        .booking-subtitle { color: var(--muted); line-height: 1.6; margin-bottom: 24px; }
        .booking-form { display: grid; gap: 19px; }
        .field-group { display: grid; gap: 8px; }
        .field-group label { color: var(--navy); font-size: 12px; font-weight: 800; }
        .field-group select { width: 100%; min-height: 46px; padding: 0 13px; color: var(--text); background: var(--surface-soft); border: 1px solid var(--border); border-radius: 10px; outline: none; }
        .field-group select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,118,110,.1); }
        .field-hint { color: var(--muted); font-size: 11px; line-height: 1.5; }
        .time-options { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
        .time-option { position: relative; }
        .time-option input { position: absolute; opacity: 0; pointer-events: none; }
        .time-option label { display: flex; justify-content: center; align-items: center; min-height: 44px; padding: 10px; border: 1px solid var(--border); border-radius: 10px; color: var(--navy); background: white; cursor: pointer; font-weight: 700; transition: .15s ease; }
        .time-option input:checked + label { border-color: var(--primary); color: var(--primary-dark); background: var(--primary-light); box-shadow: inset 0 0 0 1px var(--primary); }
        .time-option input:focus-visible + label { outline: 2px solid var(--primary); outline-offset: 2px; }
        .no-slots { padding: 16px; border: 1px dashed var(--border); border-radius: 10px; color: var(--muted); background: var(--surface-soft); text-align: center; font-size: 12px; grid-column: 1 / -1; }
        .booking-button { width: 100%; min-height: 47px; border: 0; border-radius: 10px; background: var(--primary); color: white; font-weight: 800; transition: .2s; }
        .booking-button:hover { background: var(--primary-dark); }
        .booking-button:disabled { cursor: not-allowed; opacity: .5; }
        .summary-panel { padding: 24px; }
        .summary-icon { width: 48px; height: 48px; display: grid; place-items: center; border-radius: 14px; background: var(--primary-light); color: var(--primary-dark); font-size: 23px; margin-bottom: 18px; }
        .summary-list { display: grid; gap: 0; margin-top: 18px; }
        .summary-row { display: flex; justify-content: space-between; gap: 15px; padding: 14px 0; border-bottom: 1px solid var(--border); font-size: 12px; }
        .summary-row span { color: var(--muted); }
        .summary-row strong { text-align: right; color: var(--navy); }
        .summary-footnote { margin-top: 18px; color: var(--muted); font-size: 11px; line-height: 1.6; }
        .feedback-message { display: none; margin-top: 16px; padding: 13px 15px; border-radius: 10px; line-height: 1.5; font-size: 12px; }
        .feedback-message.show { display: block; }
        .feedback-message.info { color: var(--primary-dark); background: var(--primary-light); border: 1px solid #99e4d7; }
        @media (max-width: 900px) { .booking-layout { grid-template-columns: 1fr; } }
        @media (max-width: 760px) { .patient-content { padding: 24px 16px; } .booking-panel { padding: 20px; } .time-options { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    </style>
</head>
<body>
<div class="admin-app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-top">
            <div class="sidebar-brand">
                <div class="brand-mark">VN</div>
                <div><span class="brand-name">Vitta Nexus</span><span class="brand-area">Área do paciente</span></div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <span class="nav-label">MINHA ÁREA</span>
            <a href="#inicio" class="nav-item active"><span class="nav-icon">⌂</span><span>Início</span></a>
            <a href="#agendar" class="nav-item"><span class="nav-icon">▣</span><span>Agendar consulta</span></a>
        </nav>
        <div class="sidebar-bottom">
            <a href="landing_page.html" class="nav-item"><span class="nav-icon">↩</span><span>Voltar ao site</span></a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div class="breadcrumb"><span>Vitta Nexus</span><span>›</span><strong>Agendamento</strong></div>
            <div class="topbar-actions">
                <div class="topbar-profile"><div class="profile-avatar">P</div><div><strong>Paciente</strong><span>Área do paciente</span></div></div>
            </div>
        </header>

        <div class="patient-content" id="inicio">
            <section class="page-header patient-intro">
                <div>
                    <span class="eyebrow">AGENDAMENTO ONLINE</span>
                    <h1>Agende sua consulta</h1>
                    <p>Escolha a especialidade, o dia da semana e um horário disponível.</p>
                </div>
            </section>

            <div class="patient-notice"><strong>Sistema Ativo:</strong> os horários exibidos abaixo vêm diretamente da agenda cadastrada pelos médicos no painel administrativo e são salvos com segurança no banco de dados.</div>

            <div class="booking-layout" id="agendar">
                <section class="booking-panel">
                    <span class="panel-kicker">NOVO AGENDAMENTO</span>
                    <h2>Quando você gostaria de consultar?</h2>
                    <p class="booking-subtitle">Só serão exibidos os horários cadastrados como disponíveis para a especialidade e o dia escolhidos[cite: 30].</p>

                    <form class="booking-form" id="bookingForm">
                        <div class="field-group">
                            <label for="specialty">1. Escolha a especialidade</label>
                            <select id="specialty" name="especialidade" required>
                                <option value="">Selecione uma especialidade</option>
                                <?php foreach ($especialidades as $especialidade): ?>
                                    <option value="<?= htmlspecialchars($especialidade, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($especialidade, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field-group">
                            <label for="weekday">2. Escolha o dia da semana</label>
                            <select id="weekday" name="dia_semana" required>
                                <option value="">Selecione o dia da semana</option>
                                <?php foreach ($dias as $dia): ?>
                                    <option value="<?= htmlspecialchars($dia, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($dia, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field-group">
                            <label>3. Selecione um horário disponível</label>
                            <div class="time-options" id="timeOptions" role="radiogroup" aria-label="Horários disponíveis">
                                <div class="no-slots">Escolha uma especialidade e um dia para consultar os horários.</div>
                            </div>
                            <span class="field-hint">Horários sem disponibilidade não aparecem para seleção[cite: 30].</span>
                        </div>

                        <button class="booking-button" id="bookingButton" type="submit" disabled>Confirmar escolha</button>
                    </form>
                    <div class="feedback-message" id="feedbackMessage" role="status" aria-live="polite"></div>
                </section>

                <aside class="summary-panel">
                    <div class="summary-icon">▦</div>
                    <span class="panel-kicker">RESUMO</span>
                    <h2>Sua consulta</h2>
                    <p class="booking-subtitle">Confira os dados selecionados antes de continuar.</p>
                    <div class="summary-list">
                        <div class="summary-row"><span>Especialidade</span><strong id="summarySpecialty">Não selecionada</strong></div>
                        <div class="summary-row"><span>Dia da semana</span><strong id="summaryWeekday">Não selecionado</strong></div>
                        <div class="summary-row"><span>Horário</span><strong id="summaryTime">Não selecionado</strong></div>
                    </div>
                    <p class="summary-footnote">O agendamento protege contra conflitos simultâneos através de transações seguras no banco de dados.</p>
                </aside>
            </div>

            <footer class="admin-footer" style="margin-left:0;margin-right:0;"><span>© 2026 Vitta Nexus</span><span>Área do paciente</span></footer>
        </div>
    </main>
</div>

<script>
const disponibilidade = <?= json_encode($disponibilidade, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
const specialty = document.getElementById('specialty');
const weekday = document.getElementById('weekday');
const timeOptions = document.getElementById('timeOptions');
const bookingButton = document.getElementById('bookingButton');
const feedbackMessage = document.getElementById('feedbackMessage');
let selectedTime = '';

function updateSummary() {
    document.getElementById('summarySpecialty').textContent = specialty.value || 'Não selecionada';
    document.getElementById('summaryWeekday').textContent = weekday.value || 'Não selecionado';
    document.getElementById('summaryTime').textContent = selectedTime || 'Não selecionado';
}

function renderTimes() {
    selectedTime = '';
    bookingButton.disabled = true;
    feedbackMessage.className = 'feedback-message';
    feedbackMessage.textContent = '';
    timeOptions.innerHTML = '';

    if (!specialty.value || !weekday.value) {
        timeOptions.innerHTML = '<div class="no-slots">Escolha uma especialidade e um dia para consultar os horários.</div>';
        updateSummary();
        return;
    }

    const times = (disponibilidade[specialty.value] && disponibilidade[specialty.value][weekday.value]) || [];
    if (times.length === 0) {
        timeOptions.innerHTML = '<div class="no-slots">Não há horários disponíveis para esta combinação. Escolha outro dia.</div>';
        updateSummary();
        return;
    }

    times.forEach((timeObj, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'time-option';
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'id_horario'; // Vinculado corretamente ao $_POST['id_horario'] do processamento
        input.id = 'time_' + index;
        input.value = timeObj.id; // Passa o id_horario real da tabela horarios_disponiveis
        input.required = true;
        
        input.addEventListener('change', () => {
            selectedTime = timeObj.hora;
            bookingButton.disabled = false;
            updateSummary();
        });
        
        const label = document.createElement('label');
        label.htmlFor = input.id;
        label.textContent = timeObj.hora;
        wrapper.append(input, label);
        timeOptions.appendChild(wrapper);
    });
    updateSummary();
}

specialty.addEventListener('change', renderTimes);
weekday.addEventListener('change', renderTimes);

// Envio assíncrono conectado ao script de processamento salvar_agendamento.php
document.getElementById('bookingForm').addEventListener('submit', async (event) => {
    event.preventDefault();
    
    const formData = new FormData(event.target);

    try {
        const resposta = await fetch('salvar_agendamento.php', {
            method: 'POST',
            body: formData
        });

        const dados = await resposta.json();

        feedbackMessage.textContent = dados.mensagem;
        feedbackMessage.className = 'feedback-message show ' + (dados.sucesso ? 'info' : '');

        if (dados.sucesso) {
            event.target.reset();
            setTimeout(() => {
                location.reload(); // Recarrega para remover o horário reservado da listagem
            }, 2000);
        }
    } catch (erro) {
        console.error("Erro ao agendar:", erro);
        feedbackMessage.textContent = "Erro ao comunicar com o servidor.";
        feedbackMessage.className = 'feedback-message show';
    }
});
</script>
</body>
</html>