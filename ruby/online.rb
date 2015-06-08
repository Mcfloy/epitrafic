# coding: utf-8
require 'net/http'
require 'json'
$:.unshift "lib"
require 'php_serialize'

promo = ["2017", "2018", "2019"]
villes = ["BDX", "LIL", "LYN", "MAR", "MPL", "NCY", "NAN", "NCE", "PAR", "REN", "STG", "TLS"]

promo.each do |p|
	puts "Promotion #{p}"
	villes.each do |v|
		puts "Récupération de la liste des étudiants pour #{v}..."
		uri = URI('https://epitrafic.com/dev/get/trombi.php')
		res = Net::HTTP.post_form(uri, 'login' => 'perrea_l', 'token' => '31415926', 'p' => p, 'v' => v)
		puts "Liste des étudiants obtenue."
		body = JSON.parse(res.body)
		effectif = body["effectif"]

		liste = body["liste"]
		resultat = Array.new
		i = 1
		puts "Récupération des données de chaque étudiant en cours :"
		liste.each do |user|
			uri = URI('https://intra.epitech.eu/user/' + user["login"] + '/?format=json')
			res = Net::HTTP.post_form(uri, 'login' => 'login_x', 'password' => '1234')
			body = res.body
			body.slice! "// Epitech JSON webservice ...\n"

			netsoul = 0
			json = JSON.parse(body)
			if defined?(json["locations"])
				json["locations"].each do |location|
					if location["pie"] == false && netsoul < 2
						netsoul = 1
					elsif location["pie"] == true
						netsoul = 2
					end
				end
			end
			resultat.push("login" => user["login"], "netsoul" => netsoul.to_i)
			print "\r      \r"
			print ((i.to_f / effectif) * 100).round(2)
			print "%"
			i = i + 1
		end
		puts "\nEnvoi des nouvelles données au serveur d'EPITRAFIC..."

		uri = URI('https://epitrafic.com/v3/dev/set/online.php')
		res = Net::HTTP.post_form(uri, 'login' => 'perrea_l', 'token' => '31415926', 'p' => p, 'v' => v, 'data' => resultat.to_json)

		puts "Réponse du serveur :"
		puts res.body
	end
end
