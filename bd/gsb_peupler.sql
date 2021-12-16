--
-- Contenu de la table `Visiteur`
--
INSERT INTO `Visiteur` (`idVisiteur`, `nom`, `prenom`, `login`, `mdp`, `adresse`, `cp`, `ville`, `dateEmbauche`) VALUES
('a131', 'Villechalane', 'Louis', 'lvillachane', 'jux7g', '8 rue des Charmes', '46000', 'Cahors', '2005-12-21'),
('a17', 'Andre', 'David', 'dandre', 'oppg5', '1 rue Petit', '46200', 'Lalbenque', '1998-11-23'),
('a55', 'Bedos', 'Christian', 'cbedos', 'gmhxd', '1 rue Peranud', '46250', 'Montcuq', '1995-01-12'),
('a93', 'Tusseau', 'Louis', 'ltusseau', 'ktp3s', '22 rue des Ternes', '46123', 'Gramat', '2000-05-01'),
('b13', 'Bentot', 'Pascal', 'pbentot', 'doyw1', '11 allée des Cerises', '46512', 'Bessines', '1992-07-09'),
('b16', 'Bioret', 'Luc', 'lbioret', 'hrjfs', '1 Avenue gambetta', '46000', 'Cahors', '1998-05-11'),
('b19', 'Bunisset', 'Francis', 'fbunisset', '4vbnd', '10 rue des Perles', '93100', 'Montreuil', '1987-10-21'),
('b25', 'Bunisset', 'Denise', 'dbunisset', 's1y1r', '23 rue Manin', '75019', 'paris', '2010-12-05'),
('b28', 'Cacheux', 'Bernard', 'bcacheux', 'uf7r3', '114 rue Blanche', '75017', 'Paris', '2009-11-12'),
('b34', 'Cadic', 'Eric', 'ecadic', '6u8dc', '123 avenue de la République', '75011', 'Paris', '2008-09-23'),
('b4', 'Charoze', 'Catherine', 'ccharoze', 'u817o', '100 rue Petit', '75019', 'Paris', '2005-11-12'),
('b50', 'Clepkens', 'Christophe', 'cclepkens', 'bw1us', '12 allée des Anges', '93230', 'Romainville', '2003-08-11'),
('b59', 'Cottin', 'Vincenne', 'vcottin', '2hoh9', '36 rue Des Roches', '93100', 'Monteuil', '2001-11-18'),
('d13', 'Debelle', 'Jeanne', 'jdebelle', 'nvwqq', '134 allée des Joncs', '44000', 'Nantes', '2000-05-11'),
('d51', 'Debroise', 'Michel', 'mdebroise', 'sghkb', '2 Bld Jourdain', '44000', 'Nantes', '2001-04-17'),
('e22', 'Desmarquest', 'Nathalie', 'ndesmarquest', 'f1fob', '14 Place d Arc', '45000', 'Orléans', '2005-11-12'),
('e24', 'Desnost', 'Pierre', 'pdesnost', '4k2o5', '16 avenue des Cèdres', '23200', 'Guéret', '2001-02-05'),
('e39', 'Dudouit', 'Frédéric', 'fdudouit', '44im8', '18 rue de l église', '23120', 'GrandBourg', '2000-08-01'),
('e49', 'Duncombe', 'Claude', 'cduncombe', 'qf77j', '19 rue de la tour', '23100', 'La souteraine', '1987-10-10'),
('e5', 'Enault-Pascreau', 'Céline', 'cenault', 'y2qdu', '25 place de la gare', '23200', 'Gueret', '1995-09-01'),
('e52', 'Eynde', 'Valérie', 'veynde', 'i7sn3', '3 Grand Place', '13015', 'Marseille', '1999-11-01'),
('f21', 'Finck', 'Jacques', 'jfinck', 'mpb3t', '10 avenue du Prado', '13002', 'Marseille', '2001-11-10'),
('f39', 'Frémont', 'Fernande', 'ffremont', 'xs5tq', '4 route de la mer', '13012', 'Allauh', '1998-10-01'),
('f4', 'Gest', 'Alain', 'agest', 'dywvt', '30 avenue de la mer', '13025', 'Berre', '1985-11-01');
-- --------------------------------------------------------

--
-- Contenu de la table `Etat`
--
INSERT INTO `Etat` (`idEtat`, `libelleEtat`) VALUES
('RB', 'Remboursée'),
('CL', 'Saisie clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('VA', 'Validée et mise en paiement');
-- --------------------------------------------------------

--
-- Contenu de la table `FraisForfait`
--
INSERT INTO `FraisForfait` (`idFraisForfait`, `libelleFraisForfait`, `montantFraisForfait`) VALUES
('ETP', 'Forfait Etape', 110.00),
('KM', 'Frais Kilométrique', 0.62),
('NUI', 'Nuitée Hôtel', 80.00),
('REP', 'Repas Restaurant', 25.00);
-- --------------------------------------------------------

--
-- Contenu de la table `FicheFrais`
--
INSERT INTO `FicheFrais` (`number`,`idFicheFrais`,`idVisiteur`,`date`,`nbJustificatifs`,`montantValide`,`dateModif`,`idEtat`) VALUES
(12,'a131-202112','a131','2021-12-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(11,'a131-202111','a131','2021-11-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(10,'a131-202110','a131','2021-10-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(9,'a131-202109','a131','2021-09-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(8,'a131-202108','a131','2021-08-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(8,'a131-202008','a131','2020-08-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(9,'a17-202109','a17','2021-10-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT),
(10,'a55-202110','a55','2021-10-01',DEFAULT,DEFAULT,DEFAULT,DEFAULT);
-- --------------------------------------------------------

--
-- Contenu de la table `LigneFraisForfait`
--
INSERT INTO `LigneFraisForfait` (`idFicheFrais`,`idFraisForfait`,`quantite`) VALUES
('a131-202112','ETP',40),
('a131-202112','KM',4),
('a131-202112','NUI',11),
('a131-202112','REP',4),
('a131-202111','ETP',7),
('a131-202111','KM',5),
('a131-202111','NUI',20),
('a131-202111','REP',9),
('a131-202110','ETP',7),
('a131-202110','KM',3),
('a131-202110','NUI',10),
('a131-202110','REP',9),
('a131-202109','ETP',7),
('a131-202109','KM',3),
('a131-202109','NUI',10),
('a131-202109','REP',9),
('a131-202108','ETP',7),
('a131-202108','KM',3),
('a131-202108','NUI',10),
('a131-202108','REP',9),
('a131-202008','ETP',7),
('a131-202008','KM',3),
('a131-202008','NUI',10),
('a131-202008','REP',9),
('a17-202109','ETP',4),
('a17-202109','KM',15),
('a17-202109','NUI',3),
('a17-202109','REP',3),
('a55-202110','ETP',10),
('a55-202110','KM',25),
('a55-202110','NUI',4),
('a55-202110','REP',4);
-- --------------------------------------------------------

--
-- Contenu de la table `LigneHorsFraisForfait`
--
INSERT INTO `LigneFraisHorsForfait` (`idFicheFrais`,`idLigneFraisHorsForfait`,`libelle`,`date`,`montant`) VALUES
('a131-202110',1,'taxi uber','2021-10-18',58.40),
('a131-202110',2,'salle de conférence','2021-10-18',720.40),
('a131-202110',3,'achat fournitures','2021-10-17',28.25),
('a17-202109',1,'location voiture','2021-09-08',680.70),
('a17-202109',2,'carburant','2021-09-08',88.42),
('a55-202110',1,'taxi g7','2021-10-21',78.89);
-- --------------------------------------------------------

--
-- Contenu de la table `Comptable`
--
INSERT INTO `Comptable` (`idComptable`, `nom`, `prenom`, `login`, `mdp`) VALUES
('c1', 'Smith', 'John', 'jsmith', 'azerty'),
('c2', 'Doe', 'Jane', 'jdoe', 'azerty');
-- --------------------------------------------------------
