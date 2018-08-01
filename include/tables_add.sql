/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Евгений
 * Created: 31.07.2018
 */


-- СПРАВОЧНИК СЧЕТЧИКОВ
-- Table: type_conter

-- DROP TABLE type_conter;

CREATE TABLE type_conter
(
  id serial NOT NULL, -- идентификатор
  count_name text, -- модель счетчика
  count_d integer, -- диаметр счетчика
  CONSTRAINT id_count_type PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE type_conter
  OWNER TO postgres;
COMMENT ON COLUMN type_conter.id IS 'идентификатор';
COMMENT ON COLUMN type_conter.count_name IS 'модель счетчика';
COMMENT ON COLUMN type_conter.count_d IS 'диаметр счетчика';


--Заявки на обслуживание
-- Table: tickets

-- DROP TABLE tickets;

CREATE TABLE tickets
(
  id serial NOT NULL, -- идентификатор
  plc integer, -- номер обьекта
  date_open timestamp without time zone,
  ticket_text text, -- описание заявки
  status integer, -- Статус заявки
  user_id integer,
  user_email text, -- эмейл для отправки ответа о выполнении заявки
  email_report boolean, -- отправлять ответ на почту
  CONSTRAINT id_tikets PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tickets
  OWNER TO postgres;
COMMENT ON COLUMN tickets.id IS 'идентификатор';
COMMENT ON COLUMN tickets.plc IS 'номер обьекта';
COMMENT ON COLUMN tickets.ticket_text IS 'описание заявки';
COMMENT ON COLUMN tickets.status IS 'Статус заявки';
COMMENT ON COLUMN tickets.user_email IS 'эмейл для отправки ответа о выполнении заявки';
COMMENT ON COLUMN tickets.email_report IS 'отправлять ответ на почту';




-- Справочник работ по СО
-- Table: tickets_work

-- DROP TABLE tickets_work;

CREATE TABLE tickets_work
(
  id serial NOT NULL, -- идентификатор
  name_work text, -- название проводимых работ
  count_link boolean, -- нужна ли ссылка на ресурс
  CONSTRAINT id_work PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tickets_work
  OWNER TO postgres;
COMMENT ON COLUMN tickets_work.id IS 'идентификатор';
COMMENT ON COLUMN tickets_work.name_work IS 'название проводимых работ';
COMMENT ON COLUMN tickets_work.count_link IS 'нужна ли ссылка на ресурс';

