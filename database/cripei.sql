-- CUIDADO! ATENÇÃO! PERIGO! 
-- Este arquivo só deve ser usado em tempo de desenvolvimento.
-- Quando site estiver pronto APAGUE este arquivo.

-- Apaga o banco de dados caso exista:
DROP DATABASE IF EXISTS cripei;

-- Cria o banco de dados usando UTF-8 e bustas 'case-insensitive':
CREATE DATABASE cripei CHARACTER SET utf8 COLLATE utf8_general_ci;

-- Seleciona o banco de dados:
USE cripei;

-- Cria tabela de usuários:
CREATE TABLE users (
    user_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    user_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_avatar VARCHAR(255) NOT NULL,
    user_birth DATE NOT NULL,
    user_bio TEXT,
    user_type ENUM('admin', 'author', 'moderator', 'user') DEFAULT 'user',
    user_status ENUM('on', 'off', 'deleted') DEFAULT 'on'
);

-- Cria tabela de artigos:
CREATE TABLE articles (
    art_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    art_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    art_title VARCHAR(255) NOT NULL,
    art_thumb VARCHAR(255) NOT NULL,
    art_intro VARCHAR(255) NOT NULL,
    art_author INT,
    art_content LONGTEXT NOT NULL,
    art_status ENUM('on', 'off', 'deleted') DEFAULT 'on',
    FOREIGN KEY (art_author) REFERENCES users (user_id)
);

-- Cria tabela de comentários:
CREATE TABLE comments (
    cmt_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    cmt_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cmt_author INT,
    cmt_article INT,
    cmt_content TEXT NOT NULL,
    cmt_status ENUM('on', 'off', 'deleted') DEFAULT 'on',
    FOREIGN KEY (cmt_author) REFERENCES users (user_id),
    FOREIGN KEY (cmt_article) REFERENCES articles (art_id)
);

-- Cria tabela de contatos:
CREATE TABLE contacts (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('on', 'off', 'deleted') DEFAULT 'on'
);

-- Insere alguns usuários para testes:
INSERT INTO users (
    user_name,
    user_email,
    user_password,
    user_avatar,
    user_birth,
    user_bio,
    user_type
) VALUES (
    'Joca da Silva',
    'joca@silva.com',
    SHA1('12345_Qwerty'),
    'https://randomuser.me/api/portraits/men/89.jpg',
    '2000-10-14',
    'Comentador, organizador, enrolador e coach.',
    'admin'
), (
    'Marineuza Siriliano',
    'mari@neuza.com',
    SHA1('12345_Qwerty'),
    'https://randomuser.me/api/portraits/women/22.jpg',
    '1998-02-27',
    'Especialista, modelista, arquivista e cientista.',
    'author'
), (
    'Setembrino Trocatapas',
    'set@tapas.net',
    SHA1('12345_Qwerty'),
    'https://randomuser.me/api/portraits/men/22.jpg',
    '1982-12-01',
    'Especialista em caçar o Patolino.',
    'author'
), (
    'Hermenegilda Sanguesuga',
    'hernema@sangue.suga',
    SHA1('12345_Qwerty'),
    'https://randomuser.me/api/portraits/women/12.jpg',
    '2002-03-03',
    'Formada em controle de pragas transdimensionais.',
    'author'
), (
    'Josyswaldo',
    'josy@waldinho.atc',
    SHA1('12345_Qwerty'),
    'https://randomuser.me/api/portraits/men/12.jpg',
    '2009-11-15',
    'Motorista de Uber sem rodas.',
    'user'
);

-- Insere alguns artigos para testes:
INSERT INTO articles (
    art_date,
    art_title,
    art_thumb,
    art_intro,
    art_author,
    art_content
) VALUES (
    '2022-07-01 12:44:55',
    'Primeiro artigo do Cripei.',
    'https://picsum.photos/200',
    'Como escrever um artigo que não serve para nada neste site sem assunto.',
    2,
    '<h2>Título</h2><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi quia, debitis eaque repellat quae hic fuga explicabo quos temporibus rerum! Unde numquam possimus in sint commodi tenetur sequi placeat est!</p><p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime, temporibus? Asperiores dolorem, in, facilis earum debitis nobis amet nostrum odit obcaecati velit consectetur soluta nemo enim saepe non laborum distinctio.</p><img src="https://picsum.photos/200/200" alt="Imagem aleatória"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eum inventore doloremque deserunt beatae veritatis vitae soluta magnam provident totam perferendis debitis consequatur deleniti iste impedit porro delectus, quam animi?</p><h3>Links legais:</h3><ul><li><a href="https://github.com/Luferat">GitHub do Fessô</a></li><li><a href="https://youtube.com">Vídeo legais</a></li><li><a href="https://instagram.com">Instagram da galera</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo asperiores placeat nostrum totam, a numquam ipsam reiciendis veniam vitae nihil amet quasi eum in quia, explicabo similique eos aut. Similique?</p>'
), (
    '2022-07-03 12:44:55',
    'Segundo artigo do Cripei.',
    'https://picsum.photos/199',
    'Como escrever mais um artigo que não serve para nada neste site sem assunto.',
    3,
    '<h2>Título</h2><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi quia, debitis eaque repellat quae hic fuga explicabo quos temporibus rerum! Unde numquam possimus in sint commodi tenetur sequi placeat est!</p><p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime, temporibus? Asperiores dolorem, in, facilis earum debitis nobis amet nostrum odit obcaecati velit consectetur soluta nemo enim saepe non laborum distinctio.</p><img src="https://picsum.photos/200/200" alt="Imagem aleatória"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eum inventore doloremque deserunt beatae veritatis vitae soluta magnam provident totam perferendis debitis consequatur deleniti iste impedit porro delectus, quam animi?</p><h3>Links legais:</h3><ul><li><a href="https://github.com/Luferat">GitHub do Fessô</a></li><li><a href="https://youtube.com">Vídeo legais</a></li><li><a href="https://instagram.com">Instagram da galera</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo asperiores placeat nostrum totam, a numquam ipsam reiciendis veniam vitae nihil amet quasi eum in quia, explicabo similique eos aut. Similique?</p>'
)
, (
    '2022-07-04 12:44:55',
    'Terceiro artigo do Cripei.',
    'https://picsum.photos/201',
    'Como escrever só mais um artigo que não serve para nada neste site sem assunto.',
    2,
    '<h2>Título</h2><p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quasi quia, debitis eaque repellat quae hic fuga explicabo quos temporibus rerum! Unde numquam possimus in sint commodi tenetur sequi placeat est!</p><p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maxime, temporibus? Asperiores dolorem, in, facilis earum debitis nobis amet nostrum odit obcaecati velit consectetur soluta nemo enim saepe non laborum distinctio.</p><img src="https://picsum.photos/200/200" alt="Imagem aleatória"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eum inventore doloremque deserunt beatae veritatis vitae soluta magnam provident totam perferendis debitis consequatur deleniti iste impedit porro delectus, quam animi?</p><h3>Links legais:</h3><ul><li><a href="https://github.com/Luferat">GitHub do Fessô</a></li><li><a href="https://youtube.com">Vídeo legais</a></li><li><a href="https://instagram.com">Instagram da galera</a></li></ul><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo asperiores placeat nostrum totam, a numquam ipsam reiciendis veniam vitae nihil amet quasi eum in quia, explicabo similique eos aut. Similique?</p>'
);