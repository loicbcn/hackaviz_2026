-- Pays sans données pop, sans données bien-être: BGR Bulgarie, HRV Croatie, SVN Slovénie


set variable bien_etre = 'C:\php_projects\hackaviz_2026\data\parquet_long\bien_etre.parquet';
set variable depenses_euro = 'C:\php_projects\hackaviz_2026\data\parquet_long\depenses_euro.parquet';
set variable population = 'C:\php_projects\hackaviz_2026\data\parquet_long\population.parquet';
set variable pyramide_age = 'C:\php_projects\hackaviz_2026\data\parquet_long\pyramide_age.parquet';
set variable pib = 'C:\php_projects\hackaviz_2026\data\parquet_long\pib.parquet';
set variable impots = 'C:\php_projects\hackaviz_2026\data\parquet_long\impots.parquet';
set variable dette = 'C:\php_projects\hackaviz_2026\data\parquet_long\dette.parquet';
set variable themes_finance = 'C:\php_projects\hackaviz_2026\data\themes_finance.ods';

select * from read_parquet(getvariable('dette'))
where "Unité" = 'Pourcentage du PIB' and "Année" = 2024
order by "Valeur_Mesurée" desc

from read_parquet(getvariable('depenses_euro')) d 
where substr("Cde_Dépense", 3, 2) = '06'

select distinct cde_pays, pays from read_parquet(getvariable('depenses_euro')) d
order by pays

-- budget total / pays / année
select Cde_pays cde_pays, max(Pays) pays, "Année" annee, sum(Montant) montant
from read_parquet(getvariable('depenses_euro')) d 
inner join st_read(getvariable('themes_finance')) t on t.code=substr("Cde_Dépense", 3, 2)
group by Cde_pays, "Année"

-- Dépenses par grands thèmes (2002 - 2024)
with bythemes as(
	select substr("Cde_Dépense", 3, 2) code_theme, Année annee, Cde_Pays, Pays pays, Montant montant, t.theme
	from read_parquet(getvariable('depenses_euro')) d
	inner join st_read(getvariable('themes_finance')) t on t.code =substr("Cde_Dépense", 3, 2)
	order by "Cde_Dépense"
)
select Cde_Pays, max(pays) pays, theme, sum(montant) montant
from bythemes 
group by Cde_Pays, theme
order by Cde_Pays, theme

-- Bien être (2004 - 2025)
select distinct mesure, unité from read_parquet(getvariable('bien_etre'))  order by Mesure

select "Année", pays, count(*) nb, max("Valeur_Mesurée") valeur  from read_parquet(getvariable('bien_etre'))
where mesure = 'Satisfaction à l’égard de la vie'
group by "Année", pays
order by "Année", valeur desc

-- Population (2002 - 2024)
select Cde_Pays from read_parquet(getvariable('population')) p order by année

select distinct Cde_Pays from read_parquet(getvariable('pyramide_age')) p order by année

select distinct Cde_PAys from read_parquet(getvariable('bien_etre')) order by mesure

select distinct d.cde_pays, d.pays, p.cde_pays
from read_parquet(getvariable('depenses_euro')) d
left join read_parquet(getvariable('population')) p on p.cde_pays = d.cde_pays
order by pays

select distinct d.cde_pays, d.pays, p.cde_pays
from read_parquet(getvariable('depenses_euro')) d
left join read_parquet(getvariable('bien_etre')) p on p.cde_pays = d.cde_pays
order by pays



-- Dépenses / habitant / année / domaines  
with bugets as(
	select Cde_pays cde_pays, max(Pays) pays, "Année" annee, sum(Montant) budget
	from read_parquet(getvariable('depenses_euro')) d 
	inner join st_read(getvariable('themes_finance')) t on t.code=substr("Cde_Dépense", 3, 2)
	group by Cde_pays, "Année"
), depense_pop as(
	select substr(d."Cde_Dépense", 3, 2) code_theme, d."Année" annee, d.Cde_Pays cde_pays, d.Pays pays, d.Montant montant, 
	t.theme, b."Valeur_Mesurée" valeur , p.Total, g.budget
	from read_parquet(getvariable('depenses_euro')) d
	inner join st_read(getvariable('themes_finance')) t on t.code =substr("Cde_Dépense", 3, 2)
	inner join read_parquet(getvariable('population')) p on p.Cde_Pays = d.Cde_Pays and p."Année" = d."Année"
	inner join bugets g on g.cde_pays = d.cde_pays and g.annee = d."Année"
	left join read_parquet(getvariable('bien_etre')) b on b.Cde_pays = d.Cde_pays and b."Année" = d."Année" and b.Mesure = 'Satisfaction à l’égard de la vie'
	--where d.cde_pays = 'BGR' and d.année = 2024
), all_datas as(
	select cde_pays, max(pays) pays, annee, theme, max(valeur) valeur, round(1000000000*sum((montant))/max(Total)) euros_per_hab, 100*sum(montant) / max(budget) txbudget
	from depense_pop 
	group by cde_pays, annee, theme
	order by annee, pays
)
select row_number() over(partition by annee) clst, * from (
select cde_pays, max(pays) pays, annee, max(valeur) valeur, sum(euros_per_hab) euros_per_hab
from all_datas
where valeur is not null
group by annee, cde_pays
order by annee, valeur desc
)
order by annee, valeur desc


select Cde_pays, array_agg(distinct d.année order by d.année) annees, array_agg(distinct t.theme order by t.theme), count(*) nb
from read_parquet(getvariable('depenses_euro')) d
inner join st_read(getvariable('themes_finance')) t on t.code =substr("Cde_Dépense", 3, 2)
group by Cde_pays --année, Cde_pays
order by nb desc --année, Cde_pays

select distinct année from read_parquet(getvariable('depenses_euro')) d where pays = 'France'






















--------------------------------------- Premiers tests
select * from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\depenses_euro.parquet');

select distinct Pays from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\depenses_euro.parquet')
order by Pays

select distinct "Cde_Dépense", "Dépense" 
from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\depenses_euro.parquet') 
order by "Cde_Dépense";

load spatial;

select * from st_read('C:\projets\hackaviz_2026\data\themes_finance.ods');

with bythemes as(
select substr("Cde_Dépense", 3, 2) code_theme, Année annee, Pays pays, Montant montant, t.theme
from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\depenses_euro.parquet') d
inner join st_read('C:\projets\hackaviz_2026\data\themes_finance.ods') t on t.code =substr("Cde_Dépense", 3, 2)
order by "Cde_Dépense"
)
select pays, theme, sum(montant) montant
from bythemes 
group by pays, theme
order by pays, theme


select * 
from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\bien_etre.parquet') b
where Mesure ='Satisfaction à l’égard de la vie'
order by Valeur_Mesurée desc

select distinct Mesure
from read_parquet('C:\projets\hackaviz_2026\data\parquet_long\bien_etre.parquet') b
order by Mesure


