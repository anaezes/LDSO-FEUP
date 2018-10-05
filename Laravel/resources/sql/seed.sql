------------------------------------------
--Tables

--2
CREATE TABLE country (
    id SERIAL PRIMARY KEY,
    countryName text NOT NULL UNIQUE
);


--3
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    address text,
    age SMALLINT NOT NULL,
    email text NOT NULL UNIQUE,
    name text NOT NULL,
    password text NOT NULL,
    paypalEmail text,
    phone text NOT NULL,
    postalCode text NOT NULL,
    username text NOT NULL UNIQUE,
    dateCreated TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    users_status text NOT NULL DEFAULT 'normal'::text,
    dateBanned TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateSuspended TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateTerminated  TIMESTAMP WITH TIME zone DEFAULT NULL,
    idCountry  INTEGER NOT NULL REFERENCES country(id),
    remember_token VARCHAR,
    CONSTRAINT status_ck CHECK ((users_status = ANY (ARRAY['moderator'::text, 'suspended'::text, 'banned'::text, 'normal'::text, 'terminated'::text,'admin'::text]))),
    CONSTRAINT age_ck CHECK (age>=18)
);



--4
CREATE TABLE requested_termination (
    id SERIAL PRIMARY KEY,
    dateRequested  TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    idusers INTEGER NOT NULL REFERENCES users(id)
);

--6
CREATE TABLE category (
    id SERIAL PRIMARY KEY,
    categoryName text NOT NULL UNIQUE
);

--7
CREATE TABLE publisher (
    id SERIAL PRIMARY KEY,
    publisherName  text NOT NULL UNIQUE
);

--8

CREATE TABLE language (
    id SERIAL PRIMARY KEY,
    languageName text NOT NULL UNIQUE
);


--5

CREATE TABLE auction (
    id SERIAL PRIMARY KEY,
    author  text NOT NULL,
    description  text NOT NULL,
    duration INTEGER NOT NULL,
    ISBN  text NOT NULL,
    title  text NOT NULL,
    dateCreated TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    auction_status text NOT NULL DEFAULT 'waitingApproval'::text,
    dateApproved TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateRemoved TIMESTAMP WITH TIME zone DEFAULT NULL,
    dateFinished TIMESTAMP WITH TIME zone DEFAULT NULL,
    idPublisher INTEGER REFERENCES publisher(id),
    idLanguage INTEGER NOT NULL REFERENCES language(id),
    idSeller INTEGER NOT NULL REFERENCES users(id),
    CONSTRAINT auction_status_ck CHECK ((auction_status = ANY (ARRAY['approved'::text, 'removed'::text, 'waitingApproval'::text, 'finished'::text]))),
    CONSTRAINT duration_ck CHECK (duration >= 300)
);

--9

CREATE TABLE category_auction (
    idCategory INTEGER NOT NULL REFERENCES category(id),
    idAuction INTEGER NOT NULL REFERENCES auction(id),
    CONSTRAINT category_auction_pk PRIMARY KEY (idCategory, idAuction)
);

--10
CREATE TABLE whishlist (
    idBuyer INTEGER NOT NULL REFERENCES users(id),
    idAuction INTEGER NOT NULL REFERENCES auction(id),
    CONSTRAINT wish_list_pk PRIMARY KEY (idBuyer, idAuction)
);

--11
CREATE TABLE bid (
    idBuyer INTEGER NOT NULL REFERENCES users(id),
    idAuction INTEGER NOT NULL REFERENCES auction(id),
    bidDate TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    bidValue REAL,
    CONSTRAINT bid_value_ck CHECK (bidValue > 0.0),
    CONSTRAINT bid_pk PRIMARY KEY (idBuyer, idAuction)
);

--12
CREATE TABLE auction_modification (
    id SERIAL PRIMARY KEY,
    dateRequested TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    newDescription text,
    is_approved boolean,
    dateApproved TIMESTAMP WITH TIME zone,
    idApprovedAuction INTEGER NOT NULL REFERENCES auction(id)
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

CREATE TABLE notification_auction (
    idAuction INTEGER NOT NULL REFERENCES auction(id),
    idNotification INTEGER NOT NULL REFERENCES notification(id),
    CONSTRAINT notification_auction_pk PRIMARY KEY (idAuction, idNotification)
);

--15
CREATE TABLE image (
    id SERIAL PRIMARY KEY,
    source text NOT NULL,
    idAuction  INTEGER REFERENCES auction(id),
    idAuctionModification  INTEGER REFERENCES auction_modification(id),
	idusers INTEGER REFERENCES users(id)
);

--16
CREATE TABLE message (
    id SERIAL PRIMARY KEY,
    dateSent TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    message_text text NOT NULL,
    idSender INTEGER NOT NULL REFERENCES users(id),
    idReceiver INTEGER NOT NULL REFERENCES users(id),
    CONSTRAINT check_sender_receiver CHECK (idSender != idReceiver)
);

--17
CREATE TABLE comment (
    id SERIAL PRIMARY KEY,
    datePosted TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    liked boolean,
    comment_text text NOT NULL,
    is_removed boolean DEFAULT FALSE,
    idParent INTEGER REFERENCES comment(id),
    idSender INTEGER NOT NULL REFERENCES users(id),
    idReceiver INTEGER NOT NULL REFERENCES users(id),
    CONSTRAINT check_comment_parent CHECK (id != idParent)
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

CREATE INDEX auction_id ON auction USING hash (id);

CREATE INDEX notification_id ON notification USING hash (id) WHERE is_seen = false;

CREATE INDEX bid_index ON bid USING btree (idBuyer, idAuction);

CREATE INDEX image_index ON image USING hash (id);

CREATE INDEX isbn_index ON auction USING hash (ISBN);

CREATE INDEX title_index ON auction USING GIST (to_tsvector('english', title));

CREATE INDEX author_index ON auction USING GIST (to_tsvector('english', author));


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
        ELSIF NEW.users_status='suspended' THEN
            NEW.dateSuspended:= now();
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


CREATE FUNCTION change_auction_status() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM auction WHERE NEW.id = auction.id) THEN
	    IF NEW.auction_status='approved' THEN
            NEW.dateApproved := now();
        ELSIF NEW.auction_status='removed' THEN
            NEW.dateRemoved:= now();
        END IF;
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_change_auction_status
    BEFORE UPDATE ON auction
        FOR EACH ROW
		      EXECUTE PROCEDURE change_auction_status();

--CREATE FUNCTION check_buyer() RETURNS TRIGGER AS
--$BODY$
--BEGIN
--  IF EXISTS (SELECT * FROM auction WHERE NEW.idAuction = auction.id AND NEW.idBuyer = auction.idSeller) THEN
--    RAISE EXCEPTION 'A User cant interact with is own auctions.';
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


CREATE FUNCTION check_approved_auction() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF EXISTS (SELECT * FROM auction WHERE NEW.idApprovedAuction = auction.id AND auction.auction_status != 'approved') THEN
    RAISE EXCEPTION 'A User cant request an auction modification if its not approved.';
  END IF;
  RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_check_approved_auction
  BEFORE INSERT OR UPDATE ON auction_modification
  FOR EACH ROW
    EXECUTE PROCEDURE check_approved_auction();

CREATE FUNCTION change_auction_modification_is_approved() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM auction WHERE NEW.id = auction.id) THEN
	    IF NEW.is_approved=TRUE THEN
            NEW.dateApproved := now();
        END IF;
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_change_auction_modification_is_approved
    BEFORE UPDATE ON auction_modification
        FOR EACH ROW
		      EXECUTE PROCEDURE change_auction_modification_is_approved();

CREATE FUNCTION image_auction_or_users() RETURNS TRIGGER AS
$BODY$
BEGIN

	IF (NEW.idusers!=NULL) AND (NEW.idAuction!=NULL OR NEW.idAuctionModification!= NULL) THEN
        RAISE EXCEPTION 'An image cant belong to an auction and an user';
    END IF;
	RETURN NEW;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER tr_image_auction_or_users
     BEFORE INSERT OR UPDATE ON image
        FOR EACH ROW
		      EXECUTE PROCEDURE image_auction_or_users();


--1

--2
INSERT INTO "country" (countryName) VALUES ('Austria');
INSERT INTO "country" (countryName) VALUES ('Italy');
INSERT INTO "country" (countryName) VALUES ('Belgium');
INSERT INTO "country" (countryName) VALUES ('Latvia');
INSERT INTO "country" (countryName) VALUES ('Bulgaria');
INSERT INTO "country" (countryName) VALUES ('Lithuania');
INSERT INTO "country" (countryName) VALUES ('Croatia');
INSERT INTO "country" (countryName) VALUES ('Luxembourg');
INSERT INTO "country" (countryName) VALUES ('Cyprus');
INSERT INTO "country" (countryName) VALUES ('Malta');
INSERT INTO "country" (countryName) VALUES ('Czech Republic');
INSERT INTO "country" (countryName) VALUES ('Netherlands');
INSERT INTO "country" (countryName) VALUES ('Denmark');
INSERT INTO "country" (countryName) VALUES ('Indonesia');
INSERT INTO "country" (countryName) VALUES ('Poland');
INSERT INTO "country" (countryName) VALUES ('Estonia');
INSERT INTO "country" (countryName) VALUES ('Portugal');
INSERT INTO "country" (countryName) VALUES ('Finland');
INSERT INTO "country" (countryName) VALUES ('Romania');
INSERT INTO "country" (countryName) VALUES ('France');
INSERT INTO "country" (countryName) VALUES ('Slovakia');
INSERT INTO "country" (countryName) VALUES ('Germany');
INSERT INTO "country" (countryName) VALUES ('Slovenia');
INSERT INTO "country" (countryName) VALUES ('Greece');
INSERT INTO "country" (countryName) VALUES ('Spain');
INSERT INTO "country" (countryName) VALUES ('Hungary');
INSERT INTO "country" (countryName) VALUES ('Sweden');
INSERT INTO "country" (countryName) VALUES ('Ireland');
INSERT INTO "country" (countryName) VALUES ('United Kingdom');

--8
INSERT INTO "language" (languageName) VALUES('English');
INSERT INTO "language" (languageName) VALUES('Afar');
INSERT INTO "language" (languageName) VALUES('Abkhazian');
INSERT INTO "language" (languageName) VALUES('Afrikaans');
INSERT INTO "language" (languageName) VALUES('Amharic');
INSERT INTO "language" (languageName) VALUES('Arabic');
INSERT INTO "language" (languageName) VALUES('Assamese');
INSERT INTO "language" (languageName) VALUES('Aymara');
INSERT INTO "language" (languageName) VALUES('Azerbaijani');
INSERT INTO "language" (languageName) VALUES('Bashkir');
INSERT INTO "language" (languageName) VALUES('Belarusian');
INSERT INTO "language" (languageName) VALUES('Bulgarian');
INSERT INTO "language" (languageName) VALUES('Bihari');
INSERT INTO "language" (languageName) VALUES('Bislama');
INSERT INTO "language" (languageName) VALUES('Bengali/Bangla');
INSERT INTO "language" (languageName) VALUES('Tibetan');
INSERT INTO "language" (languageName) VALUES('Breton');
INSERT INTO "language" (languageName) VALUES('Catalan');
INSERT INTO "language" (languageName) VALUES('Corsican');
INSERT INTO "language" (languageName) VALUES('Czech');
INSERT INTO "language" (languageName) VALUES('Welsh');
INSERT INTO "language" (languageName) VALUES('Danish');
INSERT INTO "language" (languageName) VALUES('German');
INSERT INTO "language" (languageName) VALUES('Bhutani');
INSERT INTO "language" (languageName) VALUES('Greek');
INSERT INTO "language" (languageName) VALUES('Esperanto');
INSERT INTO "language" (languageName) VALUES('Spanish');
INSERT INTO "language" (languageName) VALUES('Estonian');
INSERT INTO "language" (languageName) VALUES('Basque');
INSERT INTO "language" (languageName) VALUES('Persian');
INSERT INTO "language" (languageName) VALUES('Finnish');
INSERT INTO "language" (languageName) VALUES('Fiji');
INSERT INTO "language" (languageName) VALUES('Faeroese');
INSERT INTO "language" (languageName) VALUES('French');
INSERT INTO "language" (languageName) VALUES('Frisian');
INSERT INTO "language" (languageName) VALUES('Irish');
INSERT INTO "language" (languageName) VALUES('Scots/Gaelic');
INSERT INTO "language" (languageName) VALUES('Galician');
INSERT INTO "language" (languageName) VALUES('Guarani');
INSERT INTO "language" (languageName) VALUES('Gujarati');
INSERT INTO "language" (languageName) VALUES('Hausa');
INSERT INTO "language" (languageName) VALUES('Hindi');
INSERT INTO "language" (languageName) VALUES('Croatian');
INSERT INTO "language" (languageName) VALUES('Hungarian');
INSERT INTO "language" (languageName) VALUES('Armenian');
INSERT INTO "language" (languageName) VALUES('Interlingua');
INSERT INTO "language" (languageName) VALUES('Interlingue');
INSERT INTO "language" (languageName) VALUES('Inupiak');
INSERT INTO "language" (languageName) VALUES('Indonesian');
INSERT INTO "language" (languageName) VALUES('Icelandic');
INSERT INTO "language" (languageName) VALUES('Italian');
INSERT INTO "language" (languageName) VALUES('Hebrew');
INSERT INTO "language" (languageName) VALUES('Japanese');
INSERT INTO "language" (languageName) VALUES('Yiddish');
INSERT INTO "language" (languageName) VALUES('Javanese');
INSERT INTO "language" (languageName) VALUES('Georgian');
INSERT INTO "language" (languageName) VALUES('Kazakh');
INSERT INTO "language" (languageName) VALUES('Greenlandic');
INSERT INTO "language" (languageName) VALUES('Cambodian');
INSERT INTO "language" (languageName) VALUES('Kannada');
INSERT INTO "language" (languageName) VALUES('Korean');
INSERT INTO "language" (languageName) VALUES('Kashmiri');
INSERT INTO "language" (languageName) VALUES('Kurdish');
INSERT INTO "language" (languageName) VALUES('Kirghiz');
INSERT INTO "language" (languageName) VALUES('Latin');
INSERT INTO "language" (languageName) VALUES('Lingala');
INSERT INTO "language" (languageName) VALUES('Laothian');
INSERT INTO "language" (languageName) VALUES('Lithuanian');
INSERT INTO "language" (languageName) VALUES('Latvian/Lettish');
INSERT INTO "language" (languageName) VALUES('Malagasy');
INSERT INTO "language" (languageName) VALUES('Maori');
INSERT INTO "language" (languageName) VALUES('Macedonian');
INSERT INTO "language" (languageName) VALUES('Malayalam');
INSERT INTO "language" (languageName) VALUES('Mongolian');
INSERT INTO "language" (languageName) VALUES('Moldavian');
INSERT INTO "language" (languageName) VALUES('Marathi');
INSERT INTO "language" (languageName) VALUES('Malay');
INSERT INTO "language" (languageName) VALUES('Maltese');
INSERT INTO "language" (languageName) VALUES('Burmese');
INSERT INTO "language" (languageName) VALUES('Nauru');
INSERT INTO "language" (languageName) VALUES('Nepali');
INSERT INTO "language" (languageName) VALUES('Dutch');
INSERT INTO "language" (languageName) VALUES('Norwegian');
INSERT INTO "language" (languageName) VALUES('Occitan');
INSERT INTO "language" (languageName) VALUES('(Afan)/Oromoor/Oriya');
INSERT INTO "language" (languageName) VALUES('Punjabi');
INSERT INTO "language" (languageName) VALUES('Polish');
INSERT INTO "language" (languageName) VALUES('Pashto/Pushto');
INSERT INTO "language" (languageName) VALUES('Portuguese');
INSERT INTO "language" (languageName) VALUES('Quechua');
INSERT INTO "language" (languageName) VALUES('Rhaeto-Romance');
INSERT INTO "language" (languageName) VALUES('Kirundi');
INSERT INTO "language" (languageName) VALUES('Romanian');
INSERT INTO "language" (languageName) VALUES('Russian');
INSERT INTO "language" (languageName) VALUES('Kinyarwanda');
INSERT INTO "language" (languageName) VALUES('Sanskrit');
INSERT INTO "language" (languageName) VALUES('Sindhi');
INSERT INTO "language" (languageName) VALUES('Sangro');
INSERT INTO "language" (languageName) VALUES('Serbo-Croatian');
INSERT INTO "language" (languageName) VALUES('Singhalese');
INSERT INTO "language" (languageName) VALUES('Slovak');
INSERT INTO "language" (languageName) VALUES('Slovenian');
INSERT INTO "language" (languageName) VALUES('Samoan');
INSERT INTO "language" (languageName) VALUES('Shona');
INSERT INTO "language" (languageName) VALUES('Somali');
INSERT INTO "language" (languageName) VALUES('Albanian');
INSERT INTO "language" (languageName) VALUES('Serbian');
INSERT INTO "language" (languageName) VALUES('Siswati');
INSERT INTO "language" (languageName) VALUES('Sesotho');
INSERT INTO "language" (languageName) VALUES('Sundanese');
INSERT INTO "language" (languageName) VALUES('Swedish');
INSERT INTO "language" (languageName) VALUES('Swahili');
INSERT INTO "language" (languageName) VALUES('Tamil');
INSERT INTO "language" (languageName) VALUES('Telugu');
INSERT INTO "language" (languageName) VALUES('Tajik');
INSERT INTO "language" (languageName) VALUES('Thai');
INSERT INTO "language" (languageName) VALUES('Tigrinya');
INSERT INTO "language" (languageName) VALUES('Turkmen');
INSERT INTO "language" (languageName) VALUES('Tagalog');
INSERT INTO "language" (languageName) VALUES('Setswana');
INSERT INTO "language" (languageName) VALUES('Tonga');
INSERT INTO "language" (languageName) VALUES('Turkish');
INSERT INTO "language" (languageName) VALUES('Tsonga');
INSERT INTO "language" (languageName) VALUES('Tatar');
INSERT INTO "language" (languageName) VALUES('Twi');
INSERT INTO "language" (languageName) VALUES('Ukrainian');
INSERT INTO "language" (languageName) VALUES('Urdu');
INSERT INTO "language" (languageName) VALUES('Uzbek');
INSERT INTO "language" (languageName) VALUES('Vietnamese');
INSERT INTO "language" (languageName) VALUES('Volapuk');
INSERT INTO "language" (languageName) VALUES('Wolof');
INSERT INTO "language" (languageName) VALUES('Xhosa');
INSERT INTO "language" (languageName) VALUES('Yoruba');
INSERT INTO "language" (languageName) VALUES('Chinese');
INSERT INTO "language" (languageName) VALUES('Zulu');

--6
INSERT INTO "category" (categoryName) VALUES ('Arts&Music');
INSERT INTO "category" (categoryName) VALUES ('Biographies');
INSERT INTO "category" (categoryName) VALUES ('Business');
INSERT INTO "category" (categoryName) VALUES ('Kids');
INSERT INTO "category" (categoryName) VALUES ('Comics');
INSERT INTO "category" (categoryName) VALUES ('Cooking');
INSERT INTO "category" (categoryName) VALUES ('Computation&Tech');
INSERT INTO "category" (categoryName) VALUES ('Education');
INSERT INTO "category" (categoryName) VALUES ('Health&Fitness');
INSERT INTO "category" (categoryName) VALUES ('History');
INSERT INTO "category" (categoryName) VALUES ('Horror');
INSERT INTO "category" (categoryName) VALUES ('Literature');
INSERT INTO "category" (categoryName) VALUES ('Anthologies');
INSERT INTO "category" (categoryName) VALUES ('Classics');
INSERT INTO "category" (categoryName) VALUES ('Contemporary');
INSERT INTO "category" (categoryName) VALUES ('Sci-Fi&Fantasy');
INSERT INTO "category" (categoryName) VALUES ('Romance');
INSERT INTO "category" (categoryName) VALUES ('Crime');
INSERT INTO "category" (categoryName) VALUES ('Science');
INSERT INTO "category" (categoryName) VALUES ('Self-Help');
INSERT INTO "category" (categoryName) VALUES ('Travel');
INSERT INTO "category" (categoryName) VALUES ('Other');


--7
INSERT INTO "publisher" (publisherName) VALUES ('Ballantine Books');
INSERT INTO "publisher" (publisherName) VALUES ('Bloodaxe Books');
INSERT INTO "publisher" (publisherName) VALUES ('Time Inc.');
INSERT INTO "publisher" (publisherName) VALUES ('McGraw-Hill Education');
INSERT INTO "publisher" (publisherName) VALUES ('Dobson Books');
INSERT INTO "publisher" (publisherName) VALUES ('Berg Publishers ');
INSERT INTO "publisher" (publisherName) VALUES ('Vintage Books');


--3
--admin
INSERT INTO "users" (address, age, email, name, password, phone, postalCode, username, users_status, idCountry) VALUES ('admin', 21, 'admin@fe.up.pt', 'admin', '$2y$10$c1H9bNvOoNdOtoDAJDfrNOooEt7UPWTW6eeD9XTnfOL7BUGzjSpW6', '111111111', '1111-111', 'admin', 'admin', 17);
--others
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('Rua 1', 20, 'up201503616@fe.up.pt', 'Tiago Lascasas', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq', 'daniel.azevedo@fe.up.pt', '351961843043', '4510 260', 'tiagolascasas', 'normal', 17);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('Rua 2', 20, 'up201405612@fe.up.pt', 'Rúben Torres', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq', 'daniel.azevedo@fe.up.pt', '351961843043', '1234 123', 'rubentorres', 'normal', 17);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('Rua 3', 20, 'daniel.azevedo@fe.up.pt', 'Daniel Azevedo', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq', 'daniel.azevedo@fe.up.pt', '351961843043', '5453 643', 'danielazevedo', 'moderator', 17);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('Rua 4', 20, 'up201403128@fe.up.pt', 'Nelson Costa', '$2y$10$SoTxF0cqJ1La2BLl8otde.Ff97YYJfuFC3ouNBY8JGUnQf4vqacjq', 'daniel.azevedo@fe.up.pt', '351961843043', '8678 126', 'nelsoncosta', 'moderator', 17);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('2066 Straubel Court', 28, 'eluty0@bizjournals.com', 'Eustacia Luty', 'oRE56BNQ', 'eluty0@theguardian.com', '+46 904 159 9108', '692 24', 'eluty0', 'normal', 22);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('7361 Mallard Road', 19, 'ehould1@hp.com', 'Edmund Hould', 'pEU60gGA', 'ehould1@opensource.org', '+998 945 505 7553', '692 24', 'ehould1', 'normal', 3);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('5 John Wall Drive', 21, 'clittrell2@imgur.com', 'Clemmy Littrell', 'Uh4dAtIR', 'clittrell2@wunderground.com', '+63 781 701 0990', '8301', 'clittrell2', 'normal', 1);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('10 Shoshone Terrace', 48, 'ndolley3@tiny.cc', 'Nev Dolley', '9QbuY0de', 'ndolley3@paypal.com', '+62 912 696 8250', '692 24', 'ndolley3', 'normal', 1);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('6 John Wall Crossing', 56, 'nfant4@sina.com.cn', 'Nissy Fant', '2eagNED2', 'nfant4@telegraph.co.uk', '+86 998 868 7658', '692 24', 'nfant4', 'normal', 1);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('93 Moland Circle', 48, 'ihaylock5@nps.gov', 'Izak Haylock', 'Xn9P6fn9', 'ihaylock5@ca.gov', '+1 405 965 4695', '73129', 'ihaylock5', 'normal', 20);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('41185 Arrowood Street', 64, 'fblum6@bing.com', 'Filberto Blum', 'jb4VQLkf', 'fblum6@vk.com', '+46 813 516 1461', '351 88', 'fblum6', 'normal', 12);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('528 Golden Leaf Parkway', 56, 'mkees7@house.gov', 'Marietta Kees', 'uUMLA25z', 'mkees7@soundcloud.com', '+7 598 409 8497', '678030', 'mkees7', 'normal', 18);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('05804 Randy Crossing', 24, 'gblanc8@chicagotribune.com', 'Gerladina Blanc', 'ggbEHY7j', 'gblanc8@dagondesign.com', '+1 408 842 4388', '95138', 'gblanc8', 'normal', 14);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('55 Kennedy Circle', 30, 'jscanterbury9@symantec.com', 'Joceline Scanterbury', 'Z4WdQmIb', 'jscanterbury9@cbc.ca', '+86 607 522 5709', '692 24', 'jscanterbury9', 'normal', 14);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('5416 Karstens Road', 26, 'aticehursta@home.pl', 'Annamaria Ticehurst', '1acALjhr', 'aticehursta@wisc.edu', '+7 393 867 7780', '649743', 'aticehursta', 'normal', 11);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('9 Lerdahl Drive', 24, 'jmckeefryb@nationalgeographic.com', 'Jesse McKeefry', 'qz5YPb0B', 'jmckeefryb@newsvine.com', '+86 739 638 1745', '692 24', 'jmckeefryb', 'normal', 7);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('5 Leroy Point', 33, 'cjobbingsc@php.net', 'Christos Jobbings', 'm2wDFcfd', 'cjobbingsc@earthlink.net', '+351 909 729 1955', '4615-131', 'cjobbingsc', 'normal', 22);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('9894 Union Park', 52, 'cmackiewiczd@a8.net', 'Clay Mackiewicz', 'ktAA8NYY', 'cmackiewiczd@noaa.gov', '+86 520 608 3542', '692 24', 'cmackiewiczd', 'normal', 16);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('41 Pierstorff Park', 58, 'dproome@meetup.com', 'Donal Proom', 'Buq6BSfL', 'dproome@bbb.org', '+7 776 314 7169', '181518', 'dproome', 'normal', 6);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('2 Petterle Crossing', 43, 'cgristonf@admin.ch', 'Constantino Griston','uKD716by', 'cgristonf@amazon.de', '+593 422 998 3244', '692 24', 'cgristonf', 'normal', 7);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('84 Fremont Pass', 36, 'cstofferg@bigcartel.com', 'Consuelo Stoffer', 'BmrUL6gv', 'cstofferg@hatena.ne.jp', '+86 573 198 3621', '692 24', 'cstofferg', 'normal', 17);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('203 Anzinger Hill', 33, 'bselesnickh@microsoft.com', 'Brocky Selesnick', 'srH1tc53', 'bselesnickh@answers.com', '+86 150 134 1271', '692 24', 'bselesnickh', 'normal', 10);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('8 Tomscot Center', 18, 'jkennificki@upenn.edu', 'Jarvis Kennifick', 'Cc3NuZTf', 'jkennificki@cbc.ca', '+81 862 520 2384', '360-0201', 'jkennificki', 'normal', 16);
INSERT INTO "users" (address, age, email, name, password, paypalEmail, phone, postalCode, username, users_status, idCountry) VALUES ('159 Kropf Avenue', 28, 'tlinkinj@netlog.com', 'Trefor Linkin', 'ef76XTc1', 'tlinkinj@yellowpages.com', '+62 395 867 0445', '692 24', 'tlinkinj', 'normal', 18);

INSERT INTO "image" (source, idusers) VALUES ('1.jpeg',1);
INSERT INTO "image" (source, idusers) VALUES ('2.jpeg',2);
INSERT INTO "image" (source, idusers) VALUES ('3.jpeg',3);
INSERT INTO "image" (source, idusers) VALUES ('4.jpeg',4);
INSERT INTO "image" (source, idusers) VALUES ('5.jpeg',5);
INSERT INTO "image" (source, idusers) VALUES ('6.jpeg',6);
INSERT INTO "image" (source, idusers) VALUES ('7.jpeg',7);
INSERT INTO "image" (source, idusers) VALUES ('8.jpeg',8);
INSERT INTO "image" (source, idusers) VALUES ('9.jpeg',9);
INSERT INTO "image" (source, idusers) VALUES ('10.jpeg',10);
INSERT INTO "image" (source, idusers) VALUES ('11.jpeg',11);
INSERT INTO "image" (source, idusers) VALUES ('12.jpeg',12);
INSERT INTO "image" (source, idusers) VALUES ('13.jpeg',13);
INSERT INTO "image" (source, idusers) VALUES ('14.jpeg',14);
INSERT INTO "image" (source, idusers) VALUES ('15.jpeg',15);
INSERT INTO "image" (source, idusers) VALUES ('16.jpeg',16);
INSERT INTO "image" (source, idusers) VALUES ('17.jpeg',17);
INSERT INTO "image" (source, idusers) VALUES ('18.jpeg',18);
INSERT INTO "image" (source, idusers) VALUES ('19.jpeg',19);
INSERT INTO "image" (source, idusers) VALUES ('20.jpeg',20);
INSERT INTO "image" (source, idusers) VALUES ('21.jpeg',21);
INSERT INTO "image" (source, idusers) VALUES ('22.jpeg',22);
INSERT INTO "image" (source, idusers) VALUES ('23.jpeg',23);
INSERT INTO "image" (source, idusers) VALUES ('24.jpeg',24);

--4
INSERT INTO "requested_termination"  (idusers) VALUES (19);
INSERT INTO "requested_termination"  (idusers) VALUES (8);
INSERT INTO "requested_termination"  (idusers) VALUES (17);
INSERT INTO "requested_termination"  (idusers) VALUES (12);
INSERT INTO "requested_termination"  (idusers) VALUES (13);

--5
--auction data from https://www.goodreads.com/shelf/show/nobel-prize
--Real data, approved
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Gabriel Garcia Marquez', 'Very good condition', '600000', '878551550-7', 'One Hundred Years of Solitude', 'approved', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Albert Camus', 'Very good condition', '500000', '878551550-7', 'The Stranger', 'approved', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Ernest Hemingway', 'Slightly wrinkled', '450000', '878551550-7', 'The Old Man and the Sea', 'approved', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('William Golding', 'Very good condition', '600000', '878551550-7', 'Lord of the Flies', 'approved', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('J. M. Coetzee', 'Very good condition', '500000', '878551550-7', 'Disgrace', 'approved', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('John Steinbeck', 'Slightly wrinkled', '550000', '878551550-7', 'Of Mice and Men', 'approved', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Hermann Hesse', 'Very good condition', '600000', '878551550-7', 'Siddhartha (Mass Market Paperback)', 'approved', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Toni Morrison', 'Slightly wrinkled', '500000', '878551550-7', 'Beloved', 'approved', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Orhan Pamuk', 'Slightly wrinkled', '500000', '878551550-7', 'My Name is Red', 'approved', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('John Steinbeck', 'Very good condition', '600000', '878551550-7', 'The Grapes of Wrath', 'approved', 7, 1, 4, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Orhan Pamuk', 'Slightly wrinkled', '600000', '878551550-7', 'Snow', 'approved', 7, 1, 4, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Aleksandr Solzhenitsyn', 'Slightly wrinkled', '600000', '878551550-7', 'One Day in the Life of Ivan Denisovich', 'approved', 7, 1, 5, now());

--Real data to match the first auctions
INSERT INTO "image" (source, idAuction) VALUES ('years.jpg',1);
INSERT INTO "image" (source, idAuction) VALUES ('years-1.jpg',1);
INSERT INTO "image" (source, idAuction) VALUES ('the-stranger.jpg',2);
INSERT INTO "image" (source, idAuction) VALUES ('old-man.jpg',3);
INSERT INTO "image" (source, idAuction) VALUES ('flies.jpg',4);
INSERT INTO "image" (source, idAuction) VALUES ('disgrace.jpg',5);
INSERT INTO "image" (source, idAuction) VALUES ('mice.jpg',6);
INSERT INTO "image" (source, idAuction) VALUES ('sid.jpg',7);
INSERT INTO "image" (source, idAuction) VALUES ('beloved.jpg',8);
INSERT INTO "image" (source, idAuction) VALUES ('red.jpg',9);
INSERT INTO "image" (source, idAuction) VALUES ('grapes.jpg',10);
INSERT INTO "image" (source, idAuction) VALUES ('snow.jpg',11);
INSERT INTO "image" (source, idAuction) VALUES ('one-day.jpg',12);

--unapproved auctions
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Doris Lessing', 'Very good condition', '600000', '878551550-7', 'The Golden Notebook', 'waitingApproval', 5, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Albert Camus', 'Very good condition', '600000', '878551550-7', 'The Plague', 'waitingApproval', 4, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Pearl S. Buck', 'Very good condition', '600000', '878551550-7', 'The Good Earth (House of Earth, #1)', 'waitingApproval', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('William Faulkner', 'Very good condition', '600000', '878551550-7', 'The Sound and the Fury', 'waitingApproval', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Ernest Hemingway', 'Very good condition', '600000', '878551550-7', 'A Farewell to Arms', 'waitingApproval', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Gabriel Garcia Marquez', 'Very good condition', '600000', '878551550-7', 'Chronicle of a Death Foretold', 'waitingApproval', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Elfriede Jelinek', 'Very good condition', '600000', '878551550-7', 'The Piano Teacher', 'waitingApproval', 7, 1, 2, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Alice Munro', 'Very good condition', '600000', '878551550-7', 'Dear Life', 'waitingApproval', 7, 1, 3, now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved)
VALUES ('Boris Pasternak', 'Very good condition', '600000', '878551550-7', 'Doctor Zhivago', 'waitingApproval', 7, 1, 3, now());

INSERT INTO "image" (source, idAuction) VALUES ('golden.jpg',13);
INSERT INTO "image" (source, idAuction) VALUES ('plague.jpg',14);
INSERT INTO "image" (source, idAuction) VALUES ('earth.jpg',15);
INSERT INTO "image" (source, idAuction) VALUES ('sound.jpg',16);
INSERT INTO "image" (source, idAuction) VALUES ('arms.jpg',17);
INSERT INTO "image" (source, idAuction) VALUES ('death.jpg',18);
INSERT INTO "image" (source, idAuction) VALUES ('piano.jpg',19);
INSERT INTO "image" (source, idAuction) VALUES ('dear.jpg',20);
INSERT INTO "image" (source, idAuction) VALUES ('doctor.jpg',21);

--finished auctions
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Günter Grass', 'Very good condition', '600000', '878551550-7', 'The Tin Drum', 'finished', 7, 1, 3, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Hermann Hesse', 'Very good condition', '600000', '878551550-7', 'Steppenwolf', 'finished', 7, 1, 3, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Thomas Mann', 'Very good condition', '600000', '878551550-7', 'The Magic Mountain', 'finished', 7, 1, 2, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Knut Hamsun', 'Very good condition', '600000', '878551550-7', 'Hunger', 'finished', 7, 1, 2, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Svetlana Alexeivich', 'Very good condition', '600000', '878551550-7', 'Voices from Chernobyl: The Oral History of a Nuclear Disaster', 'finished', 7, 1, 3, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('John Steinbeck', 'Very good condition', '600000', '878551550-7', 'East of Eden', 'finished', 7, 1, 2, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Ernest Hemingway', 'Very good condition', '600000', '878551550-7', 'The Sun Also Rises', 'finished', 7, 1, 3, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('Samuel Beckett', 'Very good condition', '600000', '878551550-7', 'Waiting for Godot', 'finished', 7, 1, 2, now(), now());
INSERT INTO "auction" (author, description, duration, ISBN, title, auction_status, idPublisher, idLanguage, idSeller, dateApproved, dateFinished)
VALUES ('William Faulkner', 'Very good condition', '600000', '878551550-7', 'As I Lay Dying', 'finished', 7, 1, 5, now(), now());

INSERT INTO "image" (source, idAuction) VALUES ('drum.jpg',22);
INSERT INTO "image" (source, idAuction) VALUES ('steppen.jpg',23);
INSERT INTO "image" (source, idAuction) VALUES ('magic.jpg',24);
INSERT INTO "image" (source, idAuction) VALUES ('hunger.jpg',25);
INSERT INTO "image" (source, idAuction) VALUES ('voices.jpg',26);
INSERT INTO "image" (source, idAuction) VALUES ('eden.jpg',27);
INSERT INTO "image" (source, idAuction) VALUES ('sun.jpg',28);
INSERT INTO "image" (source, idAuction) VALUES ('godot.jpg',29);
INSERT INTO "image" (source, idAuction) VALUES ('dying.jpg',30);

--9
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(1,1);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(2,2);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(3,3);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(4,4);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(5,5);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(6,6);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(7,7);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(8,8);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(9,9);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(10,10);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(11,11);
INSERT INTO "category_auction" (idCategory,idAuction) VALUES(12,12);


--10
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(5,1);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(5,2);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(6,2);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(6,1);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(8,3);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(1,6);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(1,7);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(1,8);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(2,9);
INSERT INTO "whishlist" (idBuyer, idAuction) VALUES(2,10);
--11
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (16, 11, 87.61);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (16, 8, 77.29);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (16, 3, 64.57);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (18, 11, 25.72);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (18, 10, 35.96);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (11, 11, 92.11);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (19, 10, 23.92);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (19, 11, 41.46);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (11, 8, 1.02);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (20, 6, 19.26);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (20, 7, 39.37);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (20, 4, 77.13);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (11, 5, 46.32);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (9, 4, 48.03);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (9, 2, 58.62);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (19, 5, 95.52);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (15, 9, 85.09);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (15, 10, 60.05);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (17, 5, 8.21);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (16, 9, 30.02);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (11, 9, 53.92);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (13, 11, 11.07);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (16, 7, 73.97);
INSERT INTO "bid" (idBuyer, idAuction, bidValue) VALUES (15, 4, 81.92);

--12
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 1);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 2);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 3);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 4);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false', 6);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',5);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',7);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',8);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',9);
INSERT INTO "auction_modification" (newDescription, is_approved, idApprovedAuction) VALUES ('In excellent contition except for a slight wrinkling on one edge', 'false',10);

--13
INSERT INTO "notification" (information, idusers) VALUES ('neque duis bibendum morbi non quam nec dui luctus rutrum', 8);
INSERT INTO "notification" (information, idusers) VALUES ('eleifend donec ut dolor morbi vel lectus in quam fringilla rhoncus mauris enim leo rhoncus sed vestibulum sit amet', 6);
INSERT INTO "notification" (information, idusers) VALUES ('augue quam sollicitudin vitae consectetuer eget rutrum at lorem integer tincidunt ante vel ipsum praesent blandit lacinia', 2);
INSERT INTO "notification" (information, idusers) VALUES ('ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum', 13);
INSERT INTO "notification" (information, idusers) VALUES ('convallis duis consequat dui nec nisi volutpat eleifend donec ut dolor morbi vel lectus in', 7);
INSERT INTO "notification" (information, idusers) VALUES ('potenti cras in purus eu magna vulputate luctus cum sociis', 7);
INSERT INTO "notification" (information, idusers) VALUES ('et ultrices posuere cubilia curae nulla dapibus dolor vel est donec odio justo sollicitudin ut suscipit a feugiat et eros', 11);
INSERT INTO "notification" (information, idusers) VALUES ('odio consequat varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi nam ultrices libero non mattis', 9);
INSERT INTO "notification" (information, idusers) VALUES ('porttitor pede justo eu massa donec dapibus duis at velit eu est congue elementum in hac', 9);
INSERT INTO "notification" (information, idusers) VALUES ('ultrices mattis odio donec vitae nisi nam ultrices libero non mattis pulvinar nulla pede ullamcorper', 8);
INSERT INTO "notification" (information, idusers) VALUES ('nam nulla integer pede justo lacinia eget tincidunt eget tempus vel pede', 3);
INSERT INTO "notification" (information, idusers) VALUES ('sodales sed tincidunt eu felis fusce posuere felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet', 5);
INSERT INTO "notification" (information, idusers) VALUES ('eget congue eget semper rutrum nulla nunc purus phasellus in', 10);
INSERT INTO "notification" (information, idusers) VALUES ('viverra eget congue eget semper rutrum nulla nunc purus phasellus', 2);
INSERT INTO "notification" (information, idusers) VALUES ('diam id ornare imperdiet sapien urna pretium nisl ut volutpat sapien', 14);
INSERT INTO "notification" (information, idusers) VALUES ('lobortis convallis tortor risus dapibus augue vel accumsan tellus nisi', 18);
INSERT INTO "notification" (information, idusers) VALUES ('sit amet nulla quisque arcu libero rutrum ac lobortis vel dapibus', 7);
INSERT INTO "notification" (information, idusers) VALUES ('libero convallis eget eleifend luctus ultricies eu nibh quisque id justo sit', 20);
INSERT INTO "notification" (information, idusers) VALUES ('velit id pretium iaculis diam erat fermentum justo nec condimentum neque', 12);
INSERT INTO "notification" (information, idusers) VALUES ('mauris enim leo rhoncus sed vestibulum sit amet cursus id turpis integer aliquet', 14);
INSERT INTO "notification" (information, idusers) VALUES ('et commodo vulputate justo in blandit ultrices enim lorem ipsum dolor', 3);
INSERT INTO "notification" (information, idusers) VALUES ('primis in faucibus orci luctus et ultrices posuere cubilia curae duis faucibus accumsan odio', 19);
INSERT INTO "notification" (information, idusers) VALUES ('phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id', 10);
INSERT INTO "notification" (information, idusers) VALUES ('suspendisse potenti cras in purus eu magna vulputate luctus cum sociis natoque penatibus et', 17);
INSERT INTO "notification" (information, idusers) VALUES ('sit amet consectetuer adipiscing elit proin risus praesent lectus vestibulum quam sapien', 8);
INSERT INTO "notification" (information, idusers) VALUES ('orci eget orci vehicula condimentum curabitur in libero ut massa volutpat convallis morbi', 8);
INSERT INTO "notification" (information, idusers) VALUES ('felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet pulvinar sed nisl nunc rhoncus dui vel sem sed', 5);
INSERT INTO "notification" (information, idusers) VALUES ('condimentum id luctus nec molestie sed justo pellentesque viverra pede ac diam cras pellentesque volutpat', 17);
INSERT INTO "notification" (information, idusers) VALUES ('odio odio elementum eu interdum eu tincidunt in leo maecenas pulvinar lobortis est phasellus sit amet erat nulla tempus vivamus', 8);
INSERT INTO "notification" (information, idusers) VALUES ('donec dapibus duis at velit eu est congue elementum in hac habitasse platea dictumst morbi', 10);

--14
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (1, 19);
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (1, 27);
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (2, 1);
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (7, 24);
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (1, 5);
INSERT INTO "notification_auction" (idAuction, idNotification) VALUES (4, 8);

--15
--images

--16
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('cubilia curae duis faucibus accumsan odio curabitur convallis duis consequat dui nec nisi', 11, 8);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('nulla ut erat id mauris vulputate elementum nullam varius nulla', 15, 13);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('aenean auctor gravida sem praesent id massa id nisl venenatis lacinia', 17, 6);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('tincidunt lacus at velit vivamus vel nulla eget eros elementum pellentesque quisque porta volutpat', 14, 7);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('condimentum curabitur in libero ut massa volutpat convallis morbi odio odio', 11, 9);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('nulla neque libero convallis eget eleifend luctus ultricies eu nibh quisque', 20, 9);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('curabitur gravida nisi at nibh in hac habitasse platea dictumst aliquam augue quam sollicitudin vitae', 15, 14);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('neque sapien placerat ante nulla justo aliquam quis turpis eget elit sodales scelerisque mauris sit amet', 6, 4);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('ac tellus semper interdum mauris ullamcorper purus sit amet nulla quisque', 11, 5);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('consectetuer adipiscing elit proin interdum mauris non ligula pellentesque ultrices phasellus id sapien', 15, 12);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('tincidunt eu felis fusce posuere felis sed lacus morbi sem mauris laoreet ut rhoncus aliquet pulvinar sed nisl nunc rhoncus', 17, 3);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('rhoncus aliquet pulvinar sed nisl nunc rhoncus dui vel sem sed sagittis nam', 10, 12);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('ac neque duis bibendum morbi non quam nec dui luctus rutrum nulla tellus in sagittis dui vel nisl', 2, 9);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('nisl duis ac nibh fusce lacus purus aliquet at feugiat non pretium quis lectus', 11, 17);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('odio cras mi pede malesuada in imperdiet et commodo vulputate justo in blandit ultrices enim lorem', 4, 20);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('congue vivamus metus arcu adipiscing molestie hendrerit at vulputate vitae nisl aenean lectus pellentesque', 8, 11);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('blandit nam nulla integer pede justo lacinia eget tincidunt eget tempus', 5, 15);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('massa volutpat convallis morbi odio odio elementum eu interdum eu tincidunt in', 17, 10);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('dignissim vestibulum vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae', 13, 4);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('sed accumsan felis ut at dolor quis odio consequat varius integer', 10, 12);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae donec pharetra magna vestibulum aliquet ultrices erat', 14, 8);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('duis bibendum felis sed interdum venenatis turpis enim blandit mi in porttitor pede justo eu massa', 15, 6);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('erat curabitur gravida nisi at nibh in hac habitasse platea', 16, 15);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('faucibus accumsan odio curabitur convallis duis consequat dui nec nisi volutpat eleifend donec ut dolor morbi vel', 6, 10);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae nulla dapibus', 7, 8);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('pellentesque volutpat dui maecenas tristique est et tempus semper est quam pharetra magna ac consequat metus sapien', 2, 3);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('in faucibus orci luctus et ultrices posuere cubilia curae nulla dapibus dolor vel est', 3, 5);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('nullam sit amet turpis elementum ligula vehicula consequat morbi a ipsum integer a nibh in quis', 10, 17);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('primis in faucibus orci luctus et ultrices posuere cubilia curae duis faucibus accumsan odio curabitur convallis duis', 20, 3);
INSERT INTO "message" (message_text, idSender , idReceiver) VALUES ('ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae duis', 18, 19);

--17
--negative feeback with some offensive and unallowed content removed
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Terrible seller.', false, null, 8, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'A liar. Do not trust him.', false, null, 16, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'The book was completely worn out, even though he advertised it as being in perfect condition', false, null, 19, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Took way too long to arrive, unfortunately.', false, null, 15, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Does not care about his customers. Rude and without patience.', false, null, 4, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Speaks broken English. Hard to communicate with.', false, null, 6, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'The book that arrived was different from the one I bought.', false, null, 4, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Very poor quality', false, null, 3, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Does not ship soon enough', false, null, 8, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Auction times are always too long', false, null, 16, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Give me my money back you thief', true, null, 15, 12);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Please do not buy from this person, buy from me instead!', true, null, 3, 6);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'asdgdsfghdfhfghdfghdfgh', true, null, 6, 11);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'Mods will never see this huehuehue', true, null, 4, 9);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (false, 'GO TO www.totallynotascam.com TO WIN $100,000! COMPLETELY GENUINE, NO SCAM!', true, null, 3, 2);
--positive feedback
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Everything arrived as promised. Would reccomend.', false, null, 10, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Very good.', false, null, 3, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Excellent, would buy again', false, null, 4, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Great!.', false, null, 5, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Very nice seller.', false, null, 6, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Cannot get any better than this.', false, null, 7, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Nice.', false, null, 8, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Always has the best books!', false, null, 9, 2);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Cannot go wring with this one', false, null, 2, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Everything arrived as promised. Would reccomend.', false, null, 4, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Very nice seller!', false, null, 5, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Nice.', false, null, 6, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Great!', false, null, 7, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Best seller of all', false, null, 8, 3);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Everything arrived as promised. Would reccomend.', false, null, 9, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Not bad', false, null, 5, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Had exactly what I wanted', false, null, 3, 4);
INSERT INTO "comment" (liked, comment_text, is_removed, idParent, idSender , idReceiver) VALUES (true, 'Would buy again', false, null, 2, 4);
