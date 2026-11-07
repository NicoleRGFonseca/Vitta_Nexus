
CREATE DATABASE vittanexus;

USE vittanexus;

CREATE TABLE administrador (
    id_administrador INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL
);

CREATE TABLE especialidades (
    id_esp INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    id_esp INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(20) NOT NULL UNIQUE,
    celular VARCHAR(20),
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,

    FOREIGN KEY (id_esp)
        REFERENCES especialidades(id_esp)
);

CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    celular VARCHAR(20),
    convenio VARCHAR(50),
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL
);

CREATE TABLE agenda_medicos (
    id_agenda INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    dia_semana TINYINT NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    duracao_consulta INT NOT NULL DEFAULT 30,

    FOREIGN KEY (id_medico)
        REFERENCES medicos(id_medico),

    UNIQUE (id_medico, dia_semana, hora_inicio)
);

CREATE TABLE horarios_disponiveis (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    data_hora_inicio DATETIME NOT NULL,
    data_hora_fim DATETIME NOT NULL,
    status ENUM('disponivel', 'reservado', 'bloqueado')
        NOT NULL DEFAULT 'disponivel',

    FOREIGN KEY (id_medico)
        REFERENCES medicos(id_medico),

    UNIQUE (id_medico, data_hora_inicio)
);

CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_horario INT NOT NULL UNIQUE,
    id_paciente INT NOT NULL,
    status ENUM('agendada', 'confirmada', 'cancelada', 'realizada')
        NOT NULL DEFAULT 'agendada',
    observacoes TEXT,
    data_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_horario)
        REFERENCES horarios_disponiveis(id_horario),

    FOREIGN KEY (id_paciente)
        REFERENCES pacientes(id_paciente)
);

CREATE TABLE prontuarios (
    id_prontuario INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL UNIQUE,
    historico_clinico TEXT,
    alergias TEXT,
    observacoes TEXT,

    FOREIGN KEY (id_paciente)
        REFERENCES pacientes(id_paciente)
);
