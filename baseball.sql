CREATE TABLE _baseball_league (
  id bigint(11) primary key auto_increment,
  abbreviation varchar(10),
  unique (abbreviation),
  name varchar(255)
);

INSERT INTO _baseball_league (id, abbreviation, name) VALUES (1, 'mlb', 'Grandes Ligas');
INSERT INTO _baseball_league (id, abbreviation, name) VALUES (1, 'snb', 'Liga Cubana');

CREATE TABLE _baseball_team (
    id bigint(11) primary key auto_increment,
    league bigint(11),
    uid varchar(255),
    name varchar(255),
    abbreviation varchar(10),
    unique (abbreviation),
    unique (uid),
    location varchar(255),
    display_name varchar(255),
    short_display_name varchar(255),
    color varchar(6),
    alternate_color varchar(6),
    active tinyint(1) not null default 1
);

CREATE TABLE _baseball_record (
    id bigint(11) primary key auto_increment,
    team bigint(11) not null references _baseball_team(id) on update cascade on delete cascade,
    type enum ('TOTAL', 'HOME', 'ROAD'), -- registro general, como home y como visitador
    summary varchar(100),
    record_name varchar(100),
    record_value varchar(100)
);

CREATE TABLE _basebll_events(
    id bigint(11) primary key auto_increment,
    event_date datetime,
    name varchar(255),
    short_name varchar(255),
    season varchar(100),
    season_type varchar(100),
    home bigint(11) not null references _baseball_team(id) on update cascade on delete cascade,
    visitor bigint(11) not null references _baseball_team(id) on update cascade on delete cascade,
    winner bigint(11) not null references _baseball_team(id) on update cascade on delete cascade,
    home_runs integer not null default 0,
    home_errors integer not null default 0,
    home_hits integer not null default 0,
    visitor_runs integer not null default 0,
    visitor_errors integer not null default 0,
    visitor_hits integer not null default 0,
    last_inning integer not null default 9
);

CREATE TABLE _baseball_event_runs(

);


CREATE TABLE

