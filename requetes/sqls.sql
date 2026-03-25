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