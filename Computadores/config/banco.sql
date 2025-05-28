create database cadastro_computadores;
use cadastro_computadores;

create table computador (
    id int auto_increment primary key,
    sistema_operacional varchar(100),
    marca varchar(100),
    possui_placa_video boolean,
    memoria_ram_gb int,
    cpu varchar(100),
    ano int,
    imagem_url varchar(255)
);

select * from computador;
