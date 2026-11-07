<?php

require_once 'config/conexao.php';

require_once 'control/listar_medicos.php';

require_once 'control/listar_especialidade.php';



?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Vitta Nexus | Administração</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="js/admin.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- ==================== LOGIN ==================== -->
    <section class="admin-login" id="loginScreen">
        <div class="login-decoration decoration-one"></div>
        <div class="login-decoration decoration-two"></div>

        <div class="login-card">

            <div class="login-brand">
                <div class="brand-mark">VN</div>
                <div>
                    <span class="brand-name">Vitta Nexus</span>
                    <span class="brand-area">Área administrativa</span>
                </div>
            </div>

            <div class="login-heading">
                <span class="eyebrow">ACESSO RESTRITO</span>
                <h1>Bem-vindo de volta</h1>
                <p>Entre com suas credenciais para acessar o painel administrativo da clínica.</p>
            </div>

            <form id="adminLoginForm" method="POST" action="control/login_admin.php">
                <div class="form-group">
                    <label for="adminEmail">E-mail</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✉</span>
                        <input type="email" name="email" id="adminEmail" placeholder="seuemail@vittanexus.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="adminPassword">Senha</label>
                    <div class="input-wrapper">
                        <span class="input-icon">●</span>
                        <input type="password" name="senha" id="adminPassword" placeholder="Digite sua senha" required>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Mostrar senha">◉</button>
                    </div>
                </div>

                <div class="login-options">
                    <label class="check-label">
                        <input type="checkbox">
                        <span>Lembrar acesso</span>
                    </label>

                    <a href="#" onclick="return false;">Esqueci minha senha</a>
                </div>

                <button type="submit" class="primary-button login-button">
                    Entrar no painel
                    <span>→</span>
                </button>

                <p class="login-message" id="loginMessage"></p>
            </form>

            <a class="back-site" href="../index.html">← Voltar para o site</a>

            <div class="security-note">
                <span>🔒</span>
                <p>Ambiente destinado exclusivamente à equipe autorizada.</p>
            </div>
        </div>
    </section>


    <!-- ==================== DASHBOARD ==================== -->
    <div class="admin-app hidden" id="adminApp">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">

            <div class="sidebar-top">
                <div class="sidebar-brand">
                    <div class="brand-mark">VN</div>
                    <div>
                        <span class="brand-name">Vitta Nexus</span>
                        <span class="brand-area">Administração</span>
                    </div>
                </div>

                <button class="close-sidebar" id="closeSidebar" aria-label="Fechar menu">×</button>
            </div>

            <nav class="sidebar-nav">

                <span class="nav-label">VISÃO GERAL</span>

                <a href="#dashboard" class="nav-item active" data-section="dashboard">
                    <span class="nav-icon">⌂</span>
                    <span>Dashboard</span>
                </a>

                <span class="nav-label">GESTÃO</span>

                <a href="#consultas" class="nav-item" data-section="consultas">
                    <span class="nav-icon">▣</span>
                    <span>Consultas</span>
                    <span class="nav-badge">8</span>
                </a>

                <a href="#medicos" class="nav-item" data-section="medicos">
                    <span class="nav-icon">♙</span>
                    <span>Médicos</span>
                </a>

                <a href="#especialidades" class="nav-item" data-section="especialidades">
                    <span class="nav-icon">✚</span>
                    <span>Especialidades</span>
                </a>

                <a href="#pacientes" class="nav-item" data-section="pacientes">
                    <span class="nav-icon">♧</span>
                    <span>Pacientes</span>
                </a>

               <a href="#horarios" class="nav-item" data-section="horarios">
                  <span class="nav-icon">🕒</span>
                  <span>Horários</span>
               </a>

                <span class="nav-label">SISTEMA</span>

                <a href="#configuracoes" class="nav-item" data-section="configuracoes">
                    <span class="nav-icon">⚙</span>
                    <span>Configurações</span>
                </a>

            </nav>

            <div class="sidebar-bottom">
                <div class="admin-profile">
                    <div class="avatar">AD</div>
                    <div class="admin-profile-info">
                        <strong>Administrador</strong>
                        <span>Acesso autorizado</span>
                    </div>
                    <button class="logout-mini" id="logoutButton" title="Sair">↪</button>
                </div>
            </div>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main -->
        <main class="main-content">

            <header class="topbar">
                <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">☰</button>

                <div class="breadcrumb">
                    <span>Vitta Nexus</span>
                    <span>/</span>
                    <strong id="breadcrumbCurrent">Dashboard</strong>
                </div>

                <div class="topbar-actions">
                    <button class="icon-button notification-button" title="Notificações">
                        ♧
                        <span class="notification-dot"></span>
                    </button>

                    <div class="topbar-profile">
                        <div class="avatar small">AD</div>
                        <div>
                            <strong>Administrador</strong>
                            <span>Equipe Vitta Nexus</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ==================== CONSULTAS ==================== -->
            <section class="content-section" id="section-consultas">

                <div class="page-header">
                    <div>
                        <span class="eyebrow">GESTÃO</span>
                        <h1>Consultas</h1>
                        <p>Visualize e gerencie os agendamentos da clínica.</p>
                    </div>

                    <button class="primary-button" onclick="showToast('Novo agendamento disponível futuramente.')">
                        + Novo agendamento
                    </button>
                </div>

                <div class="filter-panel">
                    <div class="search-box">
                        <span>⌕</span>
                        <input type="search" placeholder="Buscar paciente ou médico...">
                    </div>

                    <select class="select-control">
                        <option>Todos os status</option>
                        <option>Confirmada</option>
                        <option>Pendente</option>
                        <option>Cancelada</option>
                    </select>

                    <select class="select-control">
                        <option>Todas as especialidades</option>
                        <option>Cardiologia</option>
                        <option>Neurologia</option>
                        <option>Pediatria</option>
                    </select>

                    <button class="outline-button">Filtrar</button>
                </div>

                <div class="table-panel">
                    <div class="table-header">
                        <div>
                            <span class="panel-kicker">AGENDAMENTOS</span>
                            <h2>Lista de consultas</h2>
                        </div>
                        <span class="table-count">248 registros</span>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Especialidade</th>
                                    <th>Data e horário</th>
                                    <th>Convênio</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td><strong>Ana Souza</strong><span class="cell-muted">CPF: ***.***.***-12</span></td>
                                    <td>Dra. Nicole</td>
                                    <td>Cardiologia</td>
                                    <td>25/09/2026 · 14:00</td>
                                    <td>Unimed</td>
                                    <td><span class="status confirmed">Confirmada</span></td>
                                    <td><button class="table-action" onclick="showToast('Detalhes da consulta')">•••</button></td>
                                </tr>

                                <tr>
                                    <td><strong>João Martins</strong><span class="cell-muted">CPF: ***.***.***-45</span></td>
                                    <td>Dra. Nathalya</td>
                                    <td>Neurologia</td>
                                    <td>25/09/2026 · 15:30</td>
                                    <td>Amil</td>
                                    <td><span class="status waiting">Pendente</span></td>
                                    <td><button class="table-action" onclick="showToast('Detalhes da consulta')">•••</button></td>
                                </tr>

                                <tr>
                                    <td><strong>Lucas Costa</strong><span class="cell-muted">CPF: ***.***.***-77</span></td>
                                    <td>Dra. Byanca</td>
                                    <td>Pediatria</td>
                                    <td>26/09/2026 · 09:00</td>
                                    <td>Bradesco Saúde</td>
                                    <td><span class="status confirmed">Confirmada</span></td>
                                    <td><button class="table-action" onclick="showToast('Detalhes da consulta')">•••</button></td>
                                </tr>

                                <tr>
                                    <td><strong>Marina Lima</strong><span class="cell-muted">CPF: ***.***.***-31</span></td>
                                    <td>Dra. Nicole</td>
                                    <td>Cardiologia</td>
                                    <td>26/09/2026 · 10:30</td>
                                    <td>Particular</td>
                                    <td><span class="status cancelled">Cancelada</span></td>
                                    <td><button class="table-action" onclick="showToast('Detalhes da consulta')">•••</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>


            <!-- ==================== MÉDICOS ==================== -->
            <section class="content-section" id="section-medicos">

                <div class="page-header">
                    <div>
                        <span class="eyebrow">GESTÃO</span>
                        <h1>Médicos</h1>
                        <p>Cadastre e organize os profissionais da clínica.</p>
                    </div>

                    <button class="primary-button" id="openDoctorModal">+ Cadastrar médico</button>
                </div>

                
<div class="doctor-grid">

    <?php if (!empty($medicos)): ?>

        <?php foreach ($medicos as $medico): ?>

            <article class="doctor-card">

                <div class="doctor-card-top">
                    <div class="doctor-avatar">
                        <?= htmlspecialchars(
                            strtoupper(substr($medico['nome'], 0, 2)),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                    <span class="active-pill">Ativo</span>
                </div>

                <h3>
                    <?= htmlspecialchars(
                        $medico['nome'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h3>

                <p>
                    Especialidade:
                    <?= htmlspecialchars(
                        (string) $medico['nome_especialidade'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <div class="doctor-details">

                    <span>
                        CRM:
                        <?= htmlspecialchars(
                            $medico['crm'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                    <span>
                        <?= htmlspecialchars(
                            $medico['email'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                </div>

                <div class="doctor-actions">
                    <button
                        type="button"
                        class="outline-button small-button"
                    >
                        Editar
                    </button>

                    <button
                        type="button"
                        class="ghost-button"
                        aria-label="Mais opções"
                    >
                        •••
                    </button>
                </div>

            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <p>Nenhum médico cadastrado.</p>

    <?php endif; ?>

</div>
</section>

<!-- ==================== GESTÃO DE HORÁRIOS ==================== -->
<section class="content-section" id="section-horarios">

    <div class="page-header">
        <div>
            <span class="eyebrow">GESTÃO DE AGENDA</span>
            <h1>Horários Disponíveis</h1>
            <p>Gerencie a disponibilidade de horários dos médicos para consulta.</p>
        </div>

        <button class="primary-button" id="openScheduleModal" type="button">
            + Gerar novos horários
        </button>
    </div>

    <!-- Painel de Filtros -->
    <div class="filter-panel">
        <div class="search-box">
            <span>⌕</span>
            <input type="search" id="filterDoctorSearch" placeholder="Buscar por médico...">
        </div>

        <select class="select-control" id="filterDoctorSpecialty">
            <option value="">Todas as especialidades</option>
            <?php foreach ($especialidades as $esp): ?>
                <option value="<?= htmlspecialchars($esp['id_esp']) ?>">
                    <?= htmlspecialchars($esp['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select class="select-control" id="filterStatus">
            <option value="">Todos os status</option>
            <option value="disponivel">Disponível</option>
            <option value="reservado">Reservado</option>
            <option value="bloqueado">Bloqueado</option>
        </select>

        <button class="outline-button" type="button">Filtrar</button>
    </div>

    <!-- Tabela de Horários Cadastrados -->
    <div class="table-panel">
        <div class="table-header">
            <div>
                <span class="panel-kicker">SLOTS DE ATENDIMENTO</span>
                <h2>Horários gerados</h2>
            </div>
            <span class="table-count">Listando registros</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Cód #</th>
                        <th>Médico</th>
                        <th>Data</th>
                        <th>Horário de Início</th>
                        <th>Horário de Fim</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query para buscar os horários cadastrados no banco
                    $sqlSlots = "SELECT h.id_horario, h.data_hora_inicio, h.data_hora_fim, h.status, m.nome AS nome_medico 
                                 FROM horarios_disponiveis h
                                 JOIN medicos m ON h.id_medico = m.id_medico
                                 ORDER BY h.data_hora_inicio DESC";
                    $stmtSlots = $pdo->query($sqlSlots);
                    $horarios = $stmtSlots->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($horarios)):
                        foreach ($horarios as $slot):
                            $inicio = new DateTime($slot['data_hora_inicio']);
                            $fim = new DateTime($slot['data_hora_fim']);
                            
                            // Define classe CSS do status
                            $statusClass = 'waiting';
                            if ($slot['status'] === 'disponivel') $statusClass = 'confirmed';
                            if ($slot['status'] === 'bloqueado') $statusClass = 'cancelled';
                    ?>
                        <tr>
                            <td><strong>#<?= $slot['id_horario'] ?></strong></td>
                            <td>
                                <strong><?= htmlspecialchars($slot['nome_medico']) ?></strong>
                            </td>
                            <td><?= $inicio->format('d/m/Y') ?></td>
                            <td><?= $inicio->format('H:i') ?></td>
                            <td><?= $fim->format('H:i') ?></td>
                            <td>
                                <span class="status <?= $statusClass ?>">
                                    <?= ucfirst($slot['status']) ?>
                                </span>
                            </td>
                            <td>
                                <button class="table-action" title="Excluir ou Bloquear">•••</button>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                Nenhum horário disponível gerado no momento.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</section>
<!-- ==================== ESPECIALIDADES ==================== -->
<section class="content-section" id="section-especialidades">

    <div class="page-header">
        <div>
            <span class="eyebrow">GESTÃO</span>
            <h1>Especialidades</h1>
            <p>Cadastre e organize as especialidades disponíveis.</p>
        </div>

        <button
            class="primary-button"
            id="openSpecialtyModal"
            type="button"
        >
            + Nova especialidade
        </button>
    </div>

    <div class="specialty-grid">

        <?php if (!empty($especialidades)): ?>

            <?php foreach ($especialidades as $esp): ?>

                <article class="specialty-card">

                    <div class="specialty-card-icon">✚</div>

                    <div>
                        <h3>
                            <?= htmlspecialchars(
                                $esp['nome'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </h3>

                        <p>
                            Cód: #<?= htmlspecialchars(
                                (string) $esp['id_esp'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </p>
                    </div>

                    <div class="specialty-footer">
                        <span>Especialidade cadastrada</span>

                        <button
                            class="ghost-button"
                            type="button"
                        >
                            Editar
                        </button>
                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <p>Nenhuma especialidade cadastrada.</p>

        <?php endif; ?>

        <article
            class="specialty-card add-specialty-card"
            id="addSpecialtyCard"
        >
            <div class="add-icon">+</div>
            <h3>Adicionar especialidade</h3>
            <p>Cadastre uma nova especialidade.</p>
        </article>

    </div>

</section>
``


            <!-- ==================== PACIENTES ==================== -->
            <section class="content-section" id="section-pacientes">

                <div class="page-header">
                    <div>
                        <span class="eyebrow">GESTÃO</span>
                        <h1>Pacientes</h1>
                        <p>Consulte os pacientes cadastrados no sistema.</p>
                    </div>

                    <button class="outline-button">Exportar lista ↓</button>
                </div>

                <div class="filter-panel">
                    <div class="search-box">
                        <span>⌕</span>
                        <input type="search" placeholder="Buscar paciente...">
                    </div>
                    <select class="select-control">
                        <option>Todos os convênios</option>
                        <option>Unimed</option>
                        <option>Amil</option>
                        <option>Bradesco Saúde</option>
                        <option>SulAmérica</option>
                        <option>Particular</option>
                    </select>
                    <button class="outline-button">Filtrar</button>
                </div>

                <div class="table-panel">
                    <div class="table-header">
                        <div>
                            <span class="panel-kicker">CADASTRO</span>
                            <h2>Pacientes cadastrados</h2>
                        </div>
                        <span class="table-count">1.284 pacientes</span>
                    </div>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Contato</th>
                                    <th>Convênio</th>
                                    <th>Última consulta</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Ana Souza</strong><span class="cell-muted">Cadastro: 08/08/2026</span></td>
                                    <td>(11) 99999-0000</td>
                                    <td>Unimed</td>
                                    <td>20/09/2026</td>
                                    <td><span class="status confirmed">Ativo</span></td>
                                    <td><button class="table-action">•••</button></td>
                                </tr>
                                <tr>
                                    <td><strong>João Martins</strong><span class="cell-muted">Cadastro: 12/08/2026</span></td>
                                    <td>(11) 98888-1111</td>
                                    <td>Amil</td>
                                    <td>18/09/2026</td>
                                    <td><span class="status confirmed">Ativo</span></td>
                                    <td><button class="table-action">•••</button></td>
                                </tr>
                                <tr>
                                    <td><strong>Lucas Costa</strong><span class="cell-muted">Cadastro: 15/08/2026</span></td>
                                    <td>(11) 97777-2222</td>
                                    <td>Bradesco Saúde</td>
                                    <td>16/09/2026</td>
                                    <td><span class="status confirmed">Ativo</span></td>
                                    <td><button class="table-action">•••</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
          

            <footer class="admin-footer">
                <span>© 2026 Vitta Nexus</span>
                <span>Área administrativa · Frontend demonstrativo</span>
            </footer>

        </main>
    </div>

    <!-- ==================== MODAL DE HORÁRIOS ==================== -->
<div class="modal-overlay" id="scheduleModal">
    <div class="modal-card small-modal">

        <button class="modal-close" data-close-modal="scheduleModal">×</button>

        <div class="modal-heading">
            <span class="modal-icon"></span>
            <div>
                <span class="eyebrow">AGENDA</span>
                <h2>Gerar Horários Disponíveis</h2>
                <p>Abra um horário de atendimento na agenda de um médico.</p>
            </div>
        </div>

        <form id="scheduleForm" class="modal-form" method ="POST" action="control/salvar_horario.php">
            <div class="form-group">
                <label for="scheduleDoctor">Selecione o Médico</label>
                <select id="scheduleDoctor" name="id_medico" required>
                    <option value="">Escolha um médico</option>
                    <?php foreach ($medicos as $medico): ?>
                        <option value="<?= htmlspecialchars($medico['id_medico']) ?>">
                            <?= htmlspecialchars($medico['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="scheduleDate">Data do Atendimento</label>
                    <input type="date" id="scheduleDate" name="data" required>
                </div>

                <div class="form-group">
                    <label for="scheduleTimeStart">Horário de Início</label>
                    <input type="time" id="scheduleTimeStart" name="hora_inicio" required>
                </div>
            </div>

            <div class="form-group">
                <label for="scheduleDuration">Duração da Consulta (Minutos)</label>
                <select id="scheduleDuration" name="duracao">
                    <option value="30" selected>30 minutos</option>
                    <option value="45">45 minutos</option>
                    <option value="60">1 hora</option>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="outline-button" data-close-modal="scheduleModal">Cancelar</button>
                <button type="submit" class="primary-button">Salvar Horário</button>
            </div>
        </form>

    </div>
</div>


    <!-- ==================== MODAL MÉDICO ==================== -->

    <div class="modal-overlay" id="doctorModal">
 <div class="modal-card">

        <h2>Cadastrar médico</h2>
   <form id="doctorForm" class="modal-form" action="control/salvar_medico.php" method="POST">

    <div class="form-grid">
        <div class="form-group full">
            <label for="doctorName">Nome completo</label>
            <!-- ADICIONADO name="nome" -->
            <input type="text" id="doctorName" name="nome" placeholder="Ex.: Dra. Maria Silva" required>
        </div>

        <div class="form-group">
            <label for="doctorCrm">CRM</label>
            <!-- ADICIONADO name="crm" -->
            <input type="text" id="doctorCrm" name="crm" placeholder="Ex.: 123456" required>
        </div>

        <div class="form-group">
            <label for="doctorSpecialty">Especialidade</label>
            

 <select id="doctorSpecialty" name="id_esp" required>
    <option value="">Selecione a especialidade</option>

    <?php foreach ($especialidades as $esp): ?>

        <option value="<?= htmlspecialchars((string) $esp['id_esp'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($esp['nome'], ENT_QUOTES, 'UTF-8') ?>
        </option>

    <?php endforeach; ?>

 </select>


        </div>

        <div class="form-group">
            <label for="doctorPhone">Telefone</label>
            <input type="tel" id="doctorPhone" name="telefone" placeholder="(11) 99999-9999">
        </div>

        <div class="form-group">
            <label for="doctorEmail">E-mail</label>
            <!-- ADICIONADO name="email" -->
            <input type="email" id="doctorEmail" name="email" placeholder="medico@email.com" required>
        </div>

        <div class="form-group">
            <label for="doctorPassword">Senha de Acesso</label>
            <!-- ADICIONADO name="senha" (Obrigatório pois seu SQL tem senha_hash NOT NULL) -->
            <input type="password" id="doctorPassword" name="senha" placeholder="Senha provisória" required>
        </div>
    </div>

    <div class="modal-actions">
        <button type="button" class="outline-button" data-close-modal="doctorModal">Cancelar</button>
        <button type="submit" class="primary-button">Cadastrar médico</button>
    </div>
 </div>
 </form>
 </div>
</div>

    <!-- ==================== MODAL ESPECIALIDADE ==================== -->
    <div class="modal-overlay" id="specialtyModal">
        <div class="modal-card small-modal">

            <button class="modal-close" data-close-modal="specialtyModal">×</button>

            <div class="modal-heading">
                <span class="modal-icon">✚</span>
                <div>
                    <span class="eyebrow">CADASTRO</span>
                    <h2>Nova especialidade</h2>
                    <p>Adicione uma nova especialidade ao sistema.</p>
                </div>
            </div>

            <form id="specialtyForm" method="POST" action="control/salvar_especialidade.php" class="modal-form">

                <div class="form-group">
                    <label for="specialtyName">Nome da especialidade</label>
                    <input type="text" id="specialtyName" name="nome" placeholder="Ex.: Dermatologia" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="outline-button" data-close-modal="specialtyModal">Cancelar</button>
                    <button type="submit" class="primary-button">Cadastrar</button>
                </div>

            </form>
        </div>
    </div>


    <div class="toast" id="toast">
        <span>✓</span>
        <p id="toastMessage">Ação realizada.</p>
    </div>


    <script>
        // =====================================================
        const adminLoginForm = document.getElementById("adminLoginForm");
const loginMessage = document.getElementById("loginMessage");

adminLoginForm.addEventListener("submit", async function(event) {
    event.preventDefault(); // Impede o recarregamento padrão da página

    const formData = new FormData(adminLoginForm);

    try {
        const resposta = await fetch("control/login_admin.php", {
            method: "POST",
            body: formData
        });

        const dados = await resposta.json();

        if (dados.sucesso) {
            // Se o login for único e correto, esconde a tela de login e abre o app
            loginScreen.classList.add("hidden");
            adminApp.classList.remove("hidden");
            loginMessage.textContent = "";
            window.scrollTo({ top: 0, behavior: "smooth" });
        } else {
            // Mostra a mensagem de erro caso o e-mail ou senha estejam errados
            loginMessage.textContent = dados.mensagem;
        }

    } catch (erro) {
        console.error("Erro no login:", erro);
        loginMessage.textContent = "Erro de conexão com o servidor.";
    }
});

        // =====================================================
        // NAVEGAÇÃO
        // =====================================================

        const navItems = document.querySelectorAll(".nav-item");
        const sections = document.querySelectorAll(".content-section");
        const breadcrumbCurrent = document.getElementById("breadcrumbCurrent");

        function openSection(sectionName) {
            sections.forEach(section => {
                section.classList.remove("active-section");
            });

            const selectedSection = document.getElementById("section-" + sectionName);

            if (selectedSection) {
                selectedSection.classList.add("active-section");
            }

            navItems.forEach(item => {
                item.classList.toggle("active", item.dataset.section === sectionName);
            });

            const activeItem = document.querySelector('.nav-item[data-section="' + sectionName + '"]');

            if (activeItem) {
                breadcrumbCurrent.textContent = activeItem.querySelector("span:nth-child(2)")?.textContent || "Dashboard";
            }

            window.scrollTo({ top: 0, behavior: "smooth" });

            closeMobileSidebar();
        }

        navItems.forEach(item => {
            item.addEventListener("click", function(event) {
                event.preventDefault();
                openSection(this.dataset.section);
            });
        });

        document.querySelectorAll("[data-section-link]").forEach(button => {
            button.addEventListener("click", function() {
                openSection(this.dataset.sectionLink);
            });
        });

        // =====================================================
        // SIDEBAR MOBILE
        // =====================================================

        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");

        document.getElementById("menuToggle").addEventListener("click", function() {
            sidebar.classList.add("mobile-open");
            sidebarOverlay.classList.add("visible");
        });

        document.getElementById("closeSidebar").addEventListener("click", closeMobileSidebar);
        sidebarOverlay.addEventListener("click", closeMobileSidebar);

        function closeMobileSidebar() {
            sidebar.classList.remove("mobile-open");
            sidebarOverlay.classList.remove("visible");
        }

        // =====================================================
        // LOGOUT
        // =====================================================

        document.getElementById("logoutButton").addEventListener("click", function() {
            adminApp.classList.add("hidden");
            loginScreen.classList.remove("hidden");
            adminLoginForm.reset();
            openSection("dashboard");
        });

        // =====================================================
        // MODAIS
        // =====================================================

        const doctorModal = document.getElementById("doctorModal");
        const specialtyModal = document.getElementById("specialtyModal");

        document.getElementById("openDoctorModal").addEventListener("click", function() {
            doctorModal.classList.add("show");
        });

        document.getElementById("openSpecialtyModal").addEventListener("click", function() {
            specialtyModal.classList.add("show");
        });

        document.getElementById("addSpecialtyCard").addEventListener("click", function() {
            specialtyModal.classList.add("show");
        });

        const openScheduleBtn = document.getElementById("openScheduleModal");
        const scheduleModal = document.getElementById("scheduleModal");

         if (openScheduleBtn && scheduleModal) {
         openScheduleBtn.addEventListener("click", () => {
         scheduleModal.classList.add("show");
        });
       }

        document.querySelectorAll("[data-close-modal]").forEach(button => {
            button.addEventListener("click", function() {
                const modalId = this.dataset.closeModal;
                document.getElementById(modalId).classList.remove("show");
            });
        });

        document.querySelectorAll(".modal-overlay").forEach(modal => {
            modal.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.classList.remove("show");
                }
            });
        });

        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                document.querySelectorAll(".modal-overlay").forEach(modal => {
                    modal.classList.remove("show");
                });
            }
        });



        // =====================================================
        // TOAST
        // =====================================================

        let toastTimer;

        function showToast(message) {
            const toast = document.getElementById("toast");
            const toastMessage = document.getElementById("toastMessage");

            toastMessage.textContent = message;
            toast.classList.add("show");

            clearTimeout(toastTimer);

            toastTimer = setTimeout(() => {
                toast.classList.remove("show");
            }, 2800);
        }
    </script>

</body>
</html>
