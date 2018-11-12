DROP TRIGGER IF EXISTS  tr_check_number_off_row_admin ON users CASCADE;
DROP TRIGGER IF EXISTS  tr_change_users_status ON users CASCADE;
DROP TRIGGER IF EXISTS  tr_change_proposal_status ON proposal CASCADE;
DROP TRIGGER IF EXISTS  tr_image_proposal ON image CASCADE;
DROP TRIGGER IF EXISTS  tr_change_proposal_modification_is_approved ON proposal_modification CASCADE;
DROP TRIGGER IF EXISTS  tr_check_approved_proposal ON proposal_modification CASCADE;

DROP FUNCTION IF EXISTS check_number_of_row_admin() CASCADE;
DROP FUNCTION IF EXISTS  change_users_status() CASCADE;
DROP FUNCTION IF EXISTS  change_proposal_status() CASCADE;
DROP FUNCTION IF EXISTS  check_approved_proposal() CASCADE;
DROP FUNCTION IF EXISTS  change_proposal_modification_is_approved() CASCADE;
DROP FUNCTION IF EXISTS image_proposal_or_users() CASCADE;

DROP TABLE IF EXISTS public.password_resets CASCADE;
DROP TABLE IF EXISTS image CASCADE;
DROP TABLE IF EXISTS notification_proposal CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS proposal_modification CASCADE;
DROP TABLE IF EXISTS bid CASCADE;
DROP TABLE IF EXISTS team CASCADE;
DROP TABLE IF EXISTS faculty_proposal CASCADE;
DROP TABLE IF EXISTS skill_user CASCADE;
DROP TABLE IF EXISTS skill_proposal CASCADE;
DROP TABLE IF EXISTS skill CASCADE;
DROP TABLE IF EXISTS proposal CASCADE;
DROP TABLE IF EXISTS requested_termination CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS faculty CASCADE;
DROP TABLE IF EXISTS team_member CASCADE;
DROP TABLE IF EXISTS team_faculty CASCADE;

--Tables

--2
CREATE TABLE faculty (
    id SERIAL PRIMARY KEY,
    facultyName text NOT NULL UNIQUE
);


--3
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email text NOT NULL UNIQUE,
    name text NOT NULL,
    password text NOT NULL,
    phone text,
    username text NOT NULL UNIQUE,
    dateCreated TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    users_status text NOT NULL DEFAULT 'normal'::text,
    dateBanned TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateSuspended TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateTerminated  TIMESTAMP WITH TIME zone DEFAULT NULL,
    idFaculty  INTEGER NOT NULL REFERENCES faculty(id),
    remember_token VARCHAR,
    CONSTRAINT status_ck CHECK ((users_status = ANY (ARRAY['moderator'::text, 'banned'::text, 'normal'::text, 'terminated'::text,'admin'::text])))
);



--4
CREATE TABLE requested_termination (
    id SERIAL PRIMARY KEY,
    dateRequested  TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    idusers INTEGER NOT NULL REFERENCES users(id)
);


--5

CREATE TABLE proposal (
    id SERIAL PRIMARY KEY,
    description  text NOT NULL,
    duration INTEGER NOT NULL,
    title  text NOT NULL,
    dateCreated TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    proposal_status text NOT NULL DEFAULT 'waitingApproval'::text,
    proposal_public boolean NOT NULL DEFAULT FALSE,
    bid_public boolean NOT NULL DEFAULT FALSE,
    dateApproved TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateRemoved TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateFinished TIMESTAMP WITH TIME zone DEFAULT NULL,
    idProponent INTEGER NOT NULL REFERENCES users(id),
    CONSTRAINT proposal_status_ck CHECK ((proposal_status = ANY (ARRAY['approved'::text, 'removed'::text, 'waitingApproval'::text, 'finished'::text]))),
    CONSTRAINT duration_ck CHECK (duration >= 300)
);

CREATE TABLE skill (
  id SERIAL PRIMARY KEY,
  skillName TEXT NOT NULL UNIQUE
);

CREATE TABLE skill_proposal(
  idSkill INTEGER NOT NULL REFERENCES skill(id),
  idProposal INTEGER NOT NULL REFERENCES proposal(id),
  PRIMARY KEY (idSkill,idProposal)
);

CREATE TABLE skill_user(
  idSkill INTEGER NOT NULL REFERENCES skill(id),
  idUser INTEGER NOT NULL REFERENCES users(id)


);

--9

CREATE TABLE faculty_proposal (
    idFaculty INTEGER NOT NULL REFERENCES faculty(id),
    idProposal INTEGER NOT NULL REFERENCES proposal(id),
    PRIMARY KEY (idFaculty,idProposal)
);

--10

CREATE TABLE team (
     id SERIAL PRIMARY KEY,
     teamName TEXT NOT NULL UNIQUE,
     idLeader INTEGER NOT NULL REFERENCES users(id),
     teamDescription TEXT NOT NULL
);

CREATE TABLE team_member(
      idTeam INTEGER NOT NULL REFERENCES team(id) ON DELETE CASCADE,
      idUser INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE team_faculty (
      idTeam INTEGER NOT NULL REFERENCES team(id) ON DELETE CASCADE,
      idFaculty INTEGER NOT NULL REFERENCES faculty(id) ON DELETE CASCADE,
      CONSTRAINT team_faculty_pk PRIMARY KEY (idTeam, idFaculty)
);


--11
CREATE TABLE bid (
    idproposal INTEGER NOT NULL REFERENCES proposal(id),
    idteam INTEGER NOT NULL REFERENCES team(id) ON DELETE CASCADE,
    bidDate TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    description text NOT NULL,
    winner boolean DEFAULT FALSE,
   -- bidValue REAL,
    --CONSTRAINT bid_value_ck CHECK (bidValue > 0.0),
    --CONSTRAINT bid_pk PRIMARY KEY (idBuyer, idproposal)
    CONSTRAINT bid_pk PRIMARY KEY (idproposal,idteam)
);

--12
CREATE TABLE proposal_modification (
    id SERIAL PRIMARY KEY,
    dateRequested TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    newDescription text,
    is_approved boolean,
    dateApproved TIMESTAMP WITH TIME zone,
    idApprovedProposal INTEGER NOT NULL REFERENCES proposal(id)
);


--13
CREATE TABLE notification (
    id SERIAL PRIMARY KEY,
    dateSent TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    information text,
    is_seen boolean DEFAULT FALSE,
    dateSeen TIMESTAMP WITH TIME zone,
    idusers INTEGER NOT NULL REFERENCES users(id)
);


--14

CREATE TABLE notification_proposal (
    idProposal INTEGER NOT NULL REFERENCES proposal(id),
    idNotification INTEGER NOT NULL REFERENCES notification(id),
    CONSTRAINT notification_proposal_pk PRIMARY KEY (idProposal, idNotification)
);

--15
CREATE TABLE image (
    id SERIAL PRIMARY KEY,
    source text NOT NULL,
    idProposalModification  INTEGER REFERENCES proposal_modification(id),
	  idusers INTEGER REFERENCES users(id)
);



CREATE TABLE public.password_resets
(
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    token character varying(255) COLLATE pg_catalog."default" NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);

-----------------------------------------------------
 --INDEXES
----------------------------------------------------

CREATE INDEX user_id ON users USING hash (id);

CREATE INDEX proposal_id ON proposal USING hash (id);

CREATE INDEX notification_id ON notification USING hash (id) WHERE is_seen = false;

--CREATE INDEX bid_index ON bid USING btree (idBuyer, idproposal);

CREATE INDEX image_index ON image USING hash (id);

--CREATE INDEX isbn_index ON proposal USING hash (ISBN);

CREATE INDEX title_index ON proposal USING GIST (to_tsvector('english', title));

--CREATE INDEX author_index ON proposal USING GIST (to_tsvector('english', author));


-----------------------------------------------------
 --TRIGGERS AND UDFs
----------------------------------------------------

CREATE FUNCTION check_number_of_row_admin() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF ((SELECT count(*) FROM users WHERE users.users_status='admin'::text) > 1)
    THEN
        RAISE EXCEPTION 'There can be only one administrator';
    END IF;
    RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_check_number_of_row_admin
  AFTER INSERT ON users
  FOR EACH ROW
    EXECUTE PROCEDURE check_number_of_row_admin();


CREATE FUNCTION change_users_status() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM users WHERE NEW.id = users.id) THEN
	    IF NEW.users_status='banned' THEN
            NEW.dateBanned := now();
        ELSIF NEW.users_status='terminated' THEN
            NEW.dateTerminated:= now();
        END IF;
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_change_users_status
    BEFORE UPDATE ON users
        FOR EACH ROW
		      EXECUTE PROCEDURE change_users_status();


CREATE FUNCTION change_proposal_status() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM proposal WHERE NEW.id = proposal.id) THEN
	    IF NEW.proposal_status='approved' THEN
            NEW.dateApproved := now();
        ELSIF NEW.proposal_status='removed' THEN
            NEW.dateRemoved:= now();
        END IF;
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_change_proposal_status
    BEFORE UPDATE ON proposal
        FOR EACH ROW
		      EXECUTE PROCEDURE change_proposal_status();

--CREATE FUNCTION check_buyer() RETURNS TRIGGER AS
--$BODY$
--BEGIN
--  IF EXISTS (SELECT * FROM proposal WHERE NEW.idproposal = proposal.id AND NEW.idBuyer = proposal.idProponent) THEN
--    RAISE EXCEPTION 'A User cant interact with is own proposals.';
--  END IF;
--  RETURN NEW;
--END
--$BODY$
--LANGUAGE plpgsql;

--CREATE TRIGGER tr_whishlist_check_buyer
--  BEFORE INSERT OR UPDATE ON whishlist
--  FOR EACH ROW
--    EXECUTE PROCEDURE check_buyer();

--CREATE TRIGGER tr_bid_check_buyer
--  BEFORE INSERT OR UPDATE ON bid
--  FOR EACH ROW
--    EXECUTE PROCEDURE check_buyer();


CREATE FUNCTION check_approved_proposal() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF EXISTS (SELECT * FROM proposal WHERE NEW.idApprovedProposal = proposal.id AND proposal.proposal_status != 'approved') THEN
    RAISE EXCEPTION 'A User cant request an proposal modification if its not approved.';
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_check_approved_proposal
  BEFORE INSERT OR UPDATE ON proposal_modification
  FOR EACH ROW
    EXECUTE PROCEDURE check_approved_proposal();

CREATE FUNCTION change_proposal_modification_is_approved() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM proposal WHERE NEW.id = proposal.id) THEN
	    IF NEW.is_approved=TRUE THEN
            NEW.dateApproved := now();
        END IF;
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_change_proposal_modification_is_approved
    BEFORE UPDATE ON proposal_modification
        FOR EACH ROW
		      EXECUTE PROCEDURE change_proposal_modification_is_approved();

CREATE FUNCTION image_proposal_or_users() RETURNS TRIGGER AS
$BODY$
BEGIN

	IF (NEW.idusers!=NULL) AND (NEW.idproposal!=NULL OR NEW.idproposalModification!= NULL) THEN
        RAISE EXCEPTION 'An image cant belong to an proposal and an user';
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_image_proposal_or_users
     BEFORE INSERT OR UPDATE ON image
        FOR EACH ROW
		      EXECUTE PROCEDURE image_proposal_or_users();


--1

--2
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Architecture');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Fine Arts');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Science');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Nutrition and Food Science');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Sports');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Law');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Economics');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Engineering');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Pharmacy');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Arts');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Medicine');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Dental Medicine');
INSERT INTO "faculty" (facultyName) VALUES ('Faculty of Psychology and Education Science');
INSERT INTO "faculty" (facultyName) VALUES ('Abel Salazar Institute of Biomedical Science');
INSERT INTO "faculty" (facultyName) VALUES ('Porto Business School');

INSERT INTO "skill" (skillName) VALUES ('Skill1');
INSERT INTO "skill" (skillName) VALUES ('Skill2');
INSERT INTO "skill" (skillName) VALUES ('Skill3');
INSERT INTO "skill" (skillName) VALUES ('Skill4');




----admin
--INSERT INTO "users" (email, name, password, phone, username, users_status, idfaculty) VALUES ('admin@fe.up.pt', 'admin', '$2y$10$c1H9bNvOoNdOtoDAJDfrNOooEt7UPWTW6eeD9XTnfOL7BUGzjSpW6', '111111111','admin', 'admin', 8);
----others
--INSERT INTO "users" (email, name, password, phone, username, users_status, idfaculty) VALUES ('up201503616@fe.up.pt', 'Daniela João', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq', '351961843043','danielajoao', 'normal', 1);
--INSERT INTO "users" (email, name, password, phone, username, users_status, idfaculty) VALUES ('up201405612@fe.up.pt', 'Nelson Costa', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq','351961843043','nelsoncosta', 'normal', 2);
--
--INSERT INTO "image" (source, idusers) VALUES ('1.jpeg',1);
--INSERT INTO "image" (source, idusers) VALUES ('2.jpeg',2);
--
----4
--INSERT INTO "requested_termination"  (idusers) VALUES (1);
--INSERT INTO "requested_termination"  (idusers) VALUES (2);
--
----5
----proposal data from https://www.goodreads.com/shelf/show/nobel-prize
----Real data, approved
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'One Hundred Years of Solitude', 'approved', TRUE, TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'A', 'approved', TRUE, TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'B', 'approved', TRUE, TRUE, 1, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'C', 'approved', TRUE, TRUE, 3, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'D', 'approved', TRUE, TRUE, 1, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'E', 'approved', TRUE, TRUE, 3, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'F', 'approved', TRUE, TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'G', 'approved', TRUE, TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status,proposal_public,bid_public, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'I', 'approved', TRUE, TRUE, 1, now());


----Real data to match the first proposals
--INSERT INTO "image" (source, idProposal) VALUES ('years.jpg',1);
--INSERT INTO "image" (source, idProposal) VALUES ('years-1.jpg',1);
--INSERT INTO "image" (source, idProposal) VALUES ('the-stranger.jpg',2);
--INSERT INTO "image" (source, idProposal) VALUES ('old-man.jpg',3);
--INSERT INTO "image" (source, idProposal) VALUES ('flies.jpg',4);
--INSERT INTO "image" (source, idProposal) VALUES ('disgrace.jpg',5);
--INSERT INTO "image" (source, idProposal) VALUES ('mice.jpg',6);
--INSERT INTO "image" (source, idProposal) VALUES ('sid.jpg',7);
--INSERT INTO "image" (source, idProposal) VALUES ('beloved.jpg',8);
--INSERT INTO "image" (source, idProposal) VALUES ('red.jpg',9);
--INSERT INTO "image" (source, idProposal) VALUES ('grapes.jpg',10);
--INSERT INTO "image" (source, idProposal) VALUES ('snow.jpg',11);
--INSERT INTO "image" (source, idProposal) VALUES ('one-day.jpg',12);
--
----unapproved proposals
--INSERT INTO "proposal" (description, duration, title, proposal_status, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'The Golden Notebook', 'waitingApproval',TRUE,TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'B', 'waitingApproval',TRUE,TRUE, 1, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'C', 'waitingApproval',TRUE,TRUE, 3, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'D', 'waitingApproval',TRUE,TRUE, 2, now());
--INSERT INTO "proposal" (description, duration, title, proposal_status, idProponent, dateApproved)
--VALUES ('Very good condition', '600000', 'E', 'waitingApproval',TRUE,TRUE, 1, now());
--
--
--
--INSERT INTO "image" (source, idProposal) VALUES ('golden.jpg',1);
--INSERT INTO "image" (source, idProposal) VALUES ('plague.jpg',2);
--INSERT INTO "image" (source, idProposal) VALUES ('earth.jpg',3);
--INSERT INTO "image" (source, idProposal) VALUES ('sound.jpg',4);
--INSERT INTO "image" (source, idProposal) VALUES ('arms.jpg',5);
--INSERT INTO "image" (source, idProposal) VALUES ('death.jpg',6);
--INSERT INTO "image" (source, idProposal) VALUES ('piano.jpg',7);
--INSERT INTO "image" (source, idProposal) VALUES ('dear.jpg',3);
--INSERT INTO "image" (source, idProposal) VALUES ('doctor.jpg',2);
--
--
--INSERT INTO "image" (source, idProposal) VALUES ('drum.jpg',2);
--INSERT INTO "image" (source, idProposal) VALUES ('steppen.jpg',1);
--INSERT INTO "image" (source, idProposal) VALUES ('magic.jpg',1);
--INSERT INTO "image" (source, idProposal) VALUES ('hunger.jpg',2);
--INSERT INTO "image" (source, idProposal) VALUES ('voices.jpg',3);
--INSERT INTO "image" (source, idProposal) VALUES ('eden.jpg',5);
--INSERT INTO "image" (source, idProposal) VALUES ('sun.jpg',9);
--INSERT INTO "image" (source, idProposal) VALUES ('godot.jpg',7);
--INSERT INTO "image" (source, idProposal) VALUES ('dying.jpg',10);
--
----9
--
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(1,1);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(2,2);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(3,3);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(4,4);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(5,5);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(6,6);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(7,7);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(8,8);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(9,9);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(10,10);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(11,11);
--INSERT INTO "faculty_proposal" (idfaculty,idproposal) VALUES(12,12);

--10



--11
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 11, 87.61);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 8, 77.29);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (3, 3, 64.57);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 10, 35.96);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (3, 11, 92.11);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 10, 23.92);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 11, 41.46);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 8, 1.02);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 6, 19.26);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 7, 39.37);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (3, 4, 77.13);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 5, 46.32);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 4, 48.03);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 2, 58.62);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (3, 5, 95.52);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (1, 9, 85.09);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (3, 10, 60.05);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 5, 8.21);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 9, 53.92);
--INSERT INTO "bid" (idBuyer, idproposal, bidValue) VALUES (2, 7, 73.97);

----12
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 1);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 2);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 3);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 4);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 6);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',5);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',7);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',8);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',9);
--INSERT INTO "proposal_modification" (newDescription, is_approved, idApprovedproposal) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',10);
--
----13
--INSERT INTO "notification" (information, idusers) VALUES ('neque duis bibendum morbi non quam nec dui luctus rutrum', 1);
--INSERT INTO "notification" (information, idusers) VALUES ('eleifend donec ut dolor morbi vel lectus in quam fringilla rhoncus mauris enim leo rhoncus sed vestibulum sit amet', 2);
--INSERT INTO "notification" (information, idusers) VALUES ('augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum praesent blandit lacinia', 2);
--INSERT INTO "notification" (information, idusers) VALUES ('ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum', 1);
--
----14
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (1, 1);
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (1, 2);
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (2, 3);
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (7, 1);
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (1, 3);
--INSERT INTO notification_proposal (idProposal, idNotification) VALUES (4, 2);
