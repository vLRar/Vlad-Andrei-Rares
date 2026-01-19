DROP TABLE IF EXISTS Accesorii;

CREATE TABLE Accesorii (
    ID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nume VARCHAR(100),
    Descriere VARCHAR(255),
    Pret INT,
    Imagine VARCHAR(255) DEFAULT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO Accesorii (Nume, Descriere, Pret) VALUES 
('Baterie Inteligenta', 'Autonomie extinsa +45 minute.', 850),
('Set Elice Low-Noise', 'Zbor mai silentios si eficient.', 120),
('Card MicroSD 256GB', 'Viteza extrema pentru filmari 5.1K.', 250),
('Geanta Hard Shell', 'Rezistenta la aps si socuri.', 450),
('Rucsac Tactic', 'Compartimentat pentru drona și laptop.', 600),
('Landing Pad Pro', 'Pista de aterizare pliabila 75cm.', 90),
('Protectie Elice 360', 'Siguranța pentru zborul în interior.', 85),
('Hub Incărcare 3-Way', 'Incarcă 3 baterii secvential.', 350),
('Statie Portabila 1000W', 'Sursa de curent pentru teren.', 2500),
('Incarcator Auto', 'Incarcă drona din mers.', 150),
('Filtre ND Set', 'ND4, ND8, ND16, ND32 pentru cinematică.', 280),
('Ochelari FPV Goggles', 'Experienta imersiva HD.', 2800),
('Controller Smart RC', 'Ecran ultra-luminos integrat.', 1500),
('Parasolar Tableta', 'Vizibilitate mai buna in soare puternic.', 70),
('Curea Controller', 'Pentru comfort în sesiuni lungi.', 50);