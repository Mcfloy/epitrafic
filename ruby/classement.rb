# coding: utf-8
require 'net/http'
require 'json'
$:.unshift "lib"
require 'php_serialize'

p = "2019"
villes = ["BDX", "LIL", "LYN", "MAR", "MPL", "NCY", "NAN", "NCE", "PAR", "REN", "STG", "TLS"]
resultat_national = Array.new

villes.each do |v|
	puts "Récupération de la liste des étudiants pour #{v}..."
	uri = URI('https://epitrafic.com/dev/get/trombi.php')
	res = Net::HTTP.post_form(uri, 'login' => 'perrea_l', 'token' => '31415926', 'p' => p, 'v' => v, 'infos' => true)
	puts "Liste des étudiants obtenue."
	body = JSON.parse(res.body)
	resultat = Array.new
	effectif = body["effectif"]

	liste = body["liste"]
	i = 1
	puts "Récupération des données de chaque étudiant en cours :"
	liste.each do |user|
		infos = PHP.unserialize(user["infos"])

		uri = URI('https://intra.epitech.eu/user/' + user["login"] + '/?format=json')
		res = Net::HTTP.post_form(uri, 'login' => 'login_x', 'password' => '1234')
		body = res.body
		body.slice! "// Epitech JSON webservice ...\n"

		json = JSON.parse(body)

		gpa = (defined?(json["gpa"][0]["gpa"]) && json["gpa"][0]["gpa"] != "n/a") ? (json["gpa"][0]["gpa"].to_f.round(2)) : (0.0)
		log = (defined?(json["nsstat"]["active"])) ? (json["nsstat"]["active"].to_f.round(2)) : (0.0)
		credits = (defined?(json["credits"])) ? (json["credits"].to_i) : (0.0)

		gpa_old = (gpa.to_f != infos["gpa"].to_f) ? infos["gpa"].to_f.round(2) : infos["gpa_old"].to_f.round(2)
		log_old = (log.to_f != infos["log"].to_f) ? infos["log"].to_f.round(2) : infos["log_old"].to_f.round(2)
		credits_old = (credits.to_i != infos["credits_old"].to_i) ? credits : infos["credits_old"]

		resultat.push("login" => user["login"], "informations_epitech" => ["classement_national_gpa" => infos["classement_national_gpa"].to_i, "classement_national_log" => infos["classement_national_log"].to_i, "classement_national_credits" => infos["classement_national_credits"].to_i, "classement_national_gpa_old" => infos["classement_national_gpa"].to_i, "classement_national_log_old" => infos["classement_national_log"].to_i, "classement_national_credits_old" => infos["classement_national_credits"].to_i, "classement_gpa" => infos["classement_gpa"].to_i, "classement_log" => infos["classement_log"].to_i, "classement_credits" => infos["classement_credits"].to_i, "classement_gpa_old" => infos["classement_gpa_old"].to_i, "classement_log_old" => infos["classement_log_old"].to_i, "classement_credits_old" => infos["classement_credits_old"], "gpa" => gpa.round(2), "log" => log.round(2), "credits" => credits, "gpa_old" => gpa_old.round(2), "log_old" => log_old.round(2), "credits_old" => credits_old.round(2)])
		print "\r      \r"
		print ((i.to_f / effectif) * 100).round(2)
		print "%"
		i = i + 1
	end
	puts "\nRécupération terminée.\nTri du nouveau classement en cours..."
	resultat.sort_by! { |a| [a['informations_epitech'].first["log"], a['informations_epitech'].first["credits"], a['informations_epitech'].first["gpa"]]}
	cpt = resultat.count
	resultat.each do |a|
		if a["informations_epitech"].first["classement_log"] != cpt
			a["informations_epitech"].first["classement_log_old"] = a["informations_epitech"].first["classement_log"].to_i
			a["informations_epitech"].first["classement_log"] = cpt
		end
		cpt -= 1
	end

	resultat.sort_by! { |a| [a['informations_epitech'].first["credits"], a['informations_epitech'].first["gpa"], a['informations_epitech'].first["log"]]}
	cpt = resultat.count
	resultat.each do |a|
		if a["informations_epitech"].first["classement_credits"] != cpt
			a["informations_epitech"].first["classement_credits_old"] = a["informations_epitech"].first["classement_credits"].to_i
			a["informations_epitech"].first["classement_credits"] = cpt
		end
		cpt -= 1
	end

	resultat.sort_by! { |a| [a['informations_epitech'].first["gpa"], a['informations_epitech'].first["credits"], a['informations_epitech'].first["log"]]}
	cpt = resultat.count
	resultat.each do |a|
		if a["informations_epitech"].first["classement_gpa"] != cpt
			a["informations_epitech"].first["classement_gpa_old"] = a["informations_epitech"].first["classement_gpa"].to_i
			a["informations_epitech"].first["classement_gpa"] = cpt
		end
		cpt -= 1
	end

	resultat_national.push(*resultat)
end

puts "\nCalcul des classements régionaux terminés.\nTri du nouveau classement national en cours..."
resultat_national.sort_by! { |a| [a['informations_epitech'].first["log"], a['informations_epitech'].first["credits"], a['informations_epitech'].first["gpa"]]}
cpt = resultat_national.count
resultat_national.each do |a|
	if a["informations_epitech"].first["classement_national_log"] != cpt
		a["informations_epitech"].first["classement_national_log_old"] = a["informations_epitech"].first["classement_national_log"].to_i
		a["informations_epitech"].first["classement_national_log"] = cpt
	end
	cpt -= 1
end

resultat_national.sort_by! { |a| [a['informations_epitech'].first["credits"], a['informations_epitech'].first["gpa"], a['informations_epitech'].first["log"]]}
cpt = resultat_national.count
resultat_national.each do |a|
	if a["informations_epitech"].first["classement_national_credits"] != cpt
		a["informations_epitech"].first["classement_national_credits_old"] = a["informations_epitech"].first["classement_national_credits"].to_i
		a["informations_epitech"].first["classement_national_credits"] = cpt
	end
	cpt -= 1
end

resultat_national.sort_by! { |a| [a['informations_epitech'].first["gpa"], a['informations_epitech'].first["credits"], a['informations_epitech'].first["log"]]}
cpt = resultat_national.count
resultat_national.each do |a|
	if a["informations_epitech"].first["classement_national_gpa"] != cpt
		a["informations_epitech"].first["classement_national_gpa_old"] = a["informations_epitech"].first["classement_national_gpa"].to_i
		a["informations_epitech"].first["classement_national_gpa"] = cpt
	end
	cpt -= 1
end


puts "Classement terminé.\nEnvoi des nouvelles données au serveur d'EPITRAFIC..."

uri = URI('https://epitrafic.com/dev/set/classement.php')
res = Net::HTTP.post_form(uri, 'login' => 'perrea_l', 'token' => '31415926', 'data' => resultat_national.to_json)

puts "Réponse du serveur :"
body = JSON.parse(res.body)
puts res.body
