# Idée

Projet CSC4101 en prenant pour :
- objet -> fruits,
- inventaire -> panier,
- galerie -> cuisine

# Tester le projet

un script `build.sh` est fourni effectuant les opérations de nettoyage/recréation
de la base de données ainsi que le loading des fixtures et le rendering du site.
Ainsi, pour tester il vous suffit d'exécuter :
```bash
chmod u+x build.sh && ./build.sh
```
Il vous suffit ensuite de vous rendre à l'addresse `127.0.0.1:[PORT]/panier` pour voir les paniers, et `127.0.0.1:[PORT]/{id}` pour voir les pages des fruits directement.

# Debugging

des commandes de debug ont été ajoutées :
```bash
php bin/console app:show-paniers
```
et :
```bash
php bin/console app:show-fruit
```
qui affichent les fruits et paniers de la base de donnée.