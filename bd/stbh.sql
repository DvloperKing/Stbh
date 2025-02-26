create database stbh;
use stbh;
create table perfil(
id int auto_increment primary key,
name_perfil varchar(20)
);
create table users(
id int auto_increment primary key,
name_user varchar(45) not null,
email varchar(45),
pass varchar(45),
id_perfil int,
foreign key (id_perfil)references perfil(id),
constraint check_nombre_sin_numeros check (name_user regexp '^[^0-9]+$')
);
create table permissions(
id decimal(3,1) primary key,
name_permissions varchar(30)
);
create table permissionsxprofile(
id_perfil int,
id_permissions decimal(3,1),
foreign key (id_perfil) references perfil(id),
foreign key (id_permissions) references permissions(id)
);