CREATE DATABASE Garage ;
USE DATABASE Garage ; 
 

CREATE TABLE notice  (  id INTEGER NOT NULL AUTO_INCREMENT ,  message VARCHAR(64) NOT NULL ,  id_visitor INTEGER NOT NULL, note INTEGER,date_creation DATETIME,  PRIMARY KEY(id), FOREIGN KEY(id_visitor) REFERENCES visitor(id) );
CREATE TABLE Timetable ( id INTEGER NOT NULL AUTO_INCREMENT, day VARCHAR(64), date DATE, timetable VARCHAR(512) NOT NULL, PRIMARY KEY(id));   
CREATE TABLE Vehicule ( id INTEGER NOT NULL AUTO_INCREMENT, brandt VARCHAR(64), model VARCHAR(64), features VARCHAR(512), year DATE, kilometers INTEGER, id_photo INTEGER, date_creation DATETIME, PRIMARY KEY(id), FOREIGN KEY(id_photo) REFERENCES Photo(id));
CREATE TABLE Visitor ( id INTEGER NOT NULL AUTO_INCREMENT, lastname VARCHAR(64), firstname VARCHAR(64), email VARCHAR(128), date_creation DATETIME, PRIMARY KEY(id));
CREATE TABLE Service ( id INTEGER NOT NULL AUTO_INCREMENT, service TEXT, date_creation DATETIME, date_modification DATETIME, PRIMARY KEY(id));   
CREATE TABLE Photo ( id INTEGER NOT NULL AUTO_INCREMENT, title VARCHAR(64) NOT NULL, url VARCHAR(256), photo BLOB NOT NULL, date_creation DATETIME );   

/*Feature notice*/
INSERT INTO notice ( id, message,id_visitor, note, date_creation) SET (1,"Nous avons été très satisfait de la dernière révision de notre véhicule", 1 ,4,now());
INSERT INTO notice ( id, message,id_visitor, note, date_creation) SET (2,"Cela fait 5 ans que nous avons remplacé notre véhicule chez M. Parrot, et il nous a donné entière satisfaction.", 2 ,5,now());
INSERT INTO notice ( id, message,id_visitor, note, date_creation) SET (3,"Les tarifs pratiqués correspondent à un très bon rapport qualité/prix. Nous recommandons ce garage.", 3 ,5,now());

/*Feature Timetable*/ 
INSERT INTO Timetable (id,day,timetable) SET (1,"default","timetable[{9h00},{12h00},{14h00 },{18h00}]") ;     

/*Feature véhicule*/ 
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (1,"Tesla","S","features[{gear:320 },{ taxgear:17},{color:red},{nbdoors:5},{moteur:electrique}]",2018,90000,32800);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (2,"Citroën","c8","features[ {gear:110 },{ taxgear:7},{color:grise},{nbdoors:5},{moteur:diesel}]",2005,93943,8990);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (3,"Renault","clio 5","features[ {gear:91 },{ taxgear:17},{color:bleue},{nbdoors:5},{moteur:hybride}]",2021,65000,14900);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (5,"Renault","Scenic 3","features[ {gear:110 },{ taxgear:5},{color:noire},{nbdoors:5}]",2015,91206,11795);  
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (4,"Peugeot","e208","features[ {gear:100 },{ taxgear:5},{color:blanche},{nbdoors:5},{moteur:electrique}]",2020,82830);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (6,"Toyota","Yaris","features[ {gear:92 },{ taxgear:5},{color:rouge},{nbdoors:5},{moteur:hybride}]",2024,9500,30650);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (7,"Fiat","500 II","features[ {gear:69 },{ taxgear:4},{color:rouge et blanche},{nbdoors:3,{moteur:essence}]",2011,129660,6490);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (8,"Volkswagen","Polo","features[ {gear:95 },{ taxgear:5},{color:blanche},{nbdoors:5},{moteur:essence}]",2018,47898,16490);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (9,"Mini","Cooper","features[ {gear:136 },{ taxgear:7},{color:bleue nuit},{nbdoors:3,{moteur:essence}]",2021,39510,22900);
INSERT INTO Vehicule (id,brandt,model, features, year, kilometers,price) SET (10,"Peugeot","3008","features[ {gear:112 },{ taxgear:6},{color:vert métallisée},{nbdoors:5},{moteur:diesel}]",2012,183750,6290);
  
INSERT INTO Visitor ( id,lastname,firstname, email,date_creation) SET (1,"Tesla","Nicolas","n.tesla@proton.me",now());  
INSERT INTO Visitor ( id,lastname,firstname, email,date_creation) SET (2,"Renault","Louis","l.renault@proton.me",now()); 
INSERT INTO Visitor ( id,lastname,firstname, email,date_creation) SET (3,"YAZAMI","Rachid","r.yazami@proton.me",now());

INSERT INTO Photo (id,title,url,photo) SET (1,"Tesla Model S","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/tesla_modelS.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Citroen c8","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/citroen_c8.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Renault Clio","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/renault_clio.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Renault Scenic 3","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/renault_scenic3.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Peugeot e208","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/peugeot_e208.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Toyota Yaris","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/toyota_yaris.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Fiat 500 II","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/fiat_500II.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Volkswagen Polo","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/volkswage_polo.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Mini Cooper","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/mini_cooper.png")); 
INSERT INTO Photo (id,title,url,photo) SET (1,"Peugeot 3008","",LOAD_FILE("c:/path/parrot-server/public/photo/garage/peugeot_3008.png"));  

INSERT INTO Service (id,service,date_creation) SET (1," {
    "services": [
        {
            "carrosserie": [
                 "retouche",
                "tollerie",
                "peinture"
                
            ]
        },
        {
            "entretien de véhicule": [
                
                     "thermiques (Vidange,checkup)",
                     "électriques",
                     "nettoyage (extérieur, intérieur)",
                     "prise en charge contrôle technique"
               
            ]
        },
        {
            "mécanique":  ["moteur"]
               
        },
        {
            "prêt de véhicule": ["véhicule de remplacement",
              "véhicule anciens",
              "véhicules haut de gamme"
              
            ]
        },
        { 
            "vente occasion": []
        },
        {
            "assistance": ["intervention de dépanneuse sur autoroute"
                
               
            ] 
        },
        { 
            "assurance": []
        }
    ]
}
",now());  

