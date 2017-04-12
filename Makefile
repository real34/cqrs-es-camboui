generate_addresses_db:
	zcat data/2014_reserve_parlementaire.json.zip | jq -C ".[] | .Adresse" | grep -v "\"\"" | grep -v "\"Mairie\"" | grep -v "\"-\"" > data/2014_addresses.csv
	zcat data/2014_reserve_parlementaire.json.zip | jq -C ".[] | .Adresse" | grep -v "\"\"" | grep -v "\"Mairie\"" | grep -v "\"-\"" > data/2014_addresses.csv