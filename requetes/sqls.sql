-- Pays sans données pop, sans données bien-être: BGR Bulgarie, HRV Croatie, SVN Slovénie
-- Lituanie sans données "dette"

set variable bien_etre = 'C:\projets\hackaviz_2026\data\parquet_long\bien_etre.parquet';
set variable depenses_euro = 'C:\projets\hackaviz_2026\data\parquet_long\depenses_euro.parquet';
set variable population = 'C:\projets\hackaviz_2026\data\parquet_long\population.parquet';
set variable pyramide_age = 'C:\projets\hackaviz_2026\data\parquet_long\pyramide_age.parquet';
set variable pib = 'C:\projets\hackaviz_2026\data\parquet_long\pib.parquet';
set variable impots = 'C:\projets\hackaviz_2026\data\parquet_long\impots.parquet';
set variable dette = 'C:\projets\hackaviz_2026\data\parquet_long\dette.parquet';
set variable themes_finance = 'C:\projets\hackaviz_2026\data\themes_finance.ods';

load  spatial;

-- Requêtes avec toutes les données.
copy(
with pyr_age as(
	select Cde_Pays cde_pays, max(Pays) pays, "Année" annee, 
	max(case when "Âge" = 'Moins de 15 ans' then "Valeur_Mesurée" else 0 end) moins_15ans,
	max(case when "Âge" = 'De 15 à 64 ans' then "Valeur_Mesurée" else 0 end) entre_15_64,
	max(case when "Âge" = '65 ans et plus' then "Valeur_Mesurée" else 0 end) sup_64
	from read_parquet(getvariable('pyramide_age'))
	where Cde_Sexe = '_T' and Mesure = 'Pourcentage de la population' 
	group by Cde_Pays, "Année"
	order by Cde_Pays, "Année"
),
dette as(
	select "Année" annee, Cde_pays cde_pays, max(Pays) pays,
	max(case when "Unité" = 'Pourcentage du PIB' then "Valeur_Mesurée" else 0 end) dette_tx_pib,
	max(case when "Unité" = 'Monnaie nationale' then "Valeur_Mesurée" else 0 end) dette_euros
	from read_parquet(getvariable('dette')) d
	group by "Année", Cde_pays
	order by "Année", Cde_pays
), impots as( 
	select Année, Cde_Pays, max(Pays) pays,  sum(Montant) montant_impot
	from read_parquet(getvariable('impots')) 
	where Cde_Transaction in('D21','D51')   -- Impôts sur le revenu et Impôts sur les produits
	group by Cde_Pays, "Année"
	order by Cde_Pays, "Année"
), all_datas as(
select d.Cde_Pays cde_pays, 
case when d.Cde_Pays = 'SVK' then 'Slovaquie' else d.Pays end pays, 
d."Cde_Dépense" cde_depense, d."Dépense" depense, d."Année" annee, d.Montant*1000000000/p.Total depense_par_hab,
t.theme,
b."Valeur_Mesurée" satisfaction,
p.Total population,
pyr.moins_15ans, pyr.entre_15_64, pyr.sup_64,
det.dette_tx_pib,
det.dette_euros * 1000000 / population dette_par_hab,
i.montant_impot * 1000000 / population impot_hab
from read_parquet(getvariable('depenses_euro')) d 
inner join st_read(getvariable('themes_finance')) t on t.code = substr("Cde_Dépense", 3, 2)
left join read_parquet(getvariable('bien_etre')) b on b.Cde_pays = d.Cde_pays and b."Année" = d."Année" and b.Mesure = 'Satisfaction à l’égard de la vie'
inner join read_parquet(getvariable('population')) p on p.Cde_Pays = d.Cde_Pays and p."Année" = d."Année"
inner join pyr_age pyr on pyr.cde_Pays = d.Cde_Pays and pyr."annee" = d."Année"
inner join dette det on det.cde_Pays = d.Cde_pays and det.annee = d."Année"
inner join impots i on i.cde_Pays = d.Cde_pays and i."Année" = d."Année" 
where d."Année">=2013 and d."Année" < 2024 
	  and length("Cde_Dépense") = 4 -- Totaux des sous-thèmes quand la valeur est sur 4 caractères (ex:GF03)
	order by d.cde_pays, d."Année"
)
select * from all_datas
where satisfaction is not null 
order by annee, pays
) to 'C:\php_projects\hackaviz_2026\data\transfo\alldatas.json' (ARRAY)


--- Classements par année par pays.

copy(
with pyr_age as(
	select Cde_Pays cde_pays, max(Pays) pays, "Année" annee, 
	max(case when "Âge" = 'Moins de 15 ans' then "Valeur_Mesurée" else 0 end) moins_15ans,
	max(case when "Âge" = 'De 15 à 64 ans' then "Valeur_Mesurée" else 0 end) entre_15_64,
	max(case when "Âge" = '65 ans et plus' then "Valeur_Mesurée" else 0 end) sup_64
	from read_parquet(getvariable('pyramide_age'))
	where Cde_Sexe = '_T' and Mesure = 'Pourcentage de la population' 
	group by Cde_Pays, "Année"
	order by Cde_Pays, "Année"
),
dette as(
	select "Année" annee, Cde_pays cde_pays, max(Pays) pays,
	max(case when "Unité" = 'Pourcentage du PIB' then "Valeur_Mesurée" else 0 end) dette_tx_pib,
	max(case when "Unité" = 'Monnaie nationale' then "Valeur_Mesurée" else 0 end) dette_euros
	from read_parquet(getvariable('dette')) d
	group by "Année", Cde_pays
	order by "Année", Cde_pays
), impots as( 
	select Année, Cde_Pays, max(Pays) pays,  sum(Montant) montant_impot
	from read_parquet(getvariable('impots')) 
	where Cde_Transaction in('D21','D51')   -- Impôts sur le revenu et Impôts sur les produits
	group by Cde_Pays, "Année"
	order by Cde_Pays, "Année"
), all_datas as(
select d.Cde_Pays cde_pays, 
case when d.Cde_Pays = 'SVK' then 'Slovaquie' else d.Pays end pays, 
d."Cde_Dépense" cde_depense, d."Dépense" depense, d."Année" annee, d.Montant*1000000000/p.Total depense_par_hab,
t.theme,
b."Valeur_Mesurée" satisfaction,
p.Total population,
pyr.moins_15ans, pyr.entre_15_64, pyr.sup_64,
det.dette_tx_pib,
det.dette_euros * 1000000 / population dette_par_hab,
i.montant_impot * 1000000 / population impot_hab
from read_parquet(getvariable('depenses_euro')) d 
inner join st_read(getvariable('themes_finance')) t on t.code = substr("Cde_Dépense", 3, 2)
left join read_parquet(getvariable('bien_etre')) b on b.Cde_pays = d.Cde_pays and b."Année" = d."Année" and b.Mesure = 'Satisfaction à l’égard de la vie'
inner join read_parquet(getvariable('population')) p on p.Cde_Pays = d.Cde_Pays and p."Année" = d."Année"
inner join pyr_age pyr on pyr.cde_Pays = d.Cde_Pays and pyr."annee" = d."Année"
inner join dette det on det.cde_Pays = d.Cde_pays and det.annee = d."Année"
inner join impots i on i.cde_Pays = d.Cde_pays and i."Année" = d."Année" 
where d."Année">=2013 and d."Année" < 2024 
	  and length("Cde_Dépense") = 4 -- Totaux des sous-thèmes quand la valeur est sur 4 caractères (ex:GF03)
	order by d.cde_pays, d."Année"
)
select row_number() over(partition by annee) clst, * from (
	select  annee, cde_pays , max(pays) pays, sum(depense_par_hab) depense_par_hab,
	max(satisfaction) satisfaction, max(population) population, 
	max(moins_15ans) moins_15ans, max(entre_15_64) entre_15_64, max(sup_64) sup_64, 
	max(dette_tx_pib) dette_tx_pib, max(dette_par_hab) dette_par_hab, max(impot_hab) impot_hab
	from all_datas
	where satisfaction is not null
	group by annee, cde_pays
	order by annee, satisfaction desc
)
) to 'C:\php_projects\hackaviz_2026\data\transfo\classements.json' (ARRAY)