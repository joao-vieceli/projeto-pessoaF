## Script DML para criação da tabela Postgres

CREATE TABLE pessoas( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      data_nascimento date   NOT NULL  , 
      cpf varchar  (11)   NOT NULL  , 
      sexo char   NOT NULL  , 
      fone varchar  (10)   , 
      email varchar  (25)   NOT NULL  , 
      rua varchar  (20)   , 
      numero integer   NOT NULL  , 
      complemento text   , 
      cidade varchar  (100)   NOT NULL  , 
      uf char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

 
 ALTER TABLE pessoas ADD UNIQUE (email);
 
  
SELECT setval('pessoas_id_seq', coalesce(max(id),0) + 1, false) FROM pessoas;