<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Language FR strings for the coursebadges module.
 *
 * @package     mod_coursebadges
 * @copyright   2020 Reseau-Canope
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['available_badges'] = 'Badges disponibles';

$string['btn_change_badge_selections'] = 'Modifier les badges';
$string['btn_save_selected_badge'] = 'Enregistrer mon choix';

$string['cbx_allow_modifications_choice'] = 'Autoriser la modification du choix (par défaut : oui)';
$string['cbx_always_show_results'] = 'Toujours afficher les résultats aux étudiants';
$string['cbx_no_notification'] = 'Pas de notification';
$string['cbx_no_publish_results'] = 'Ne pas publier les résultats aux étudiants';
$string['cbx_show_course_badges_block'] = 'Afficher le bloc "Choix de badge"';
$string['cbx_show_results_after_response'] = 'Afficher les résultats aux étudiants après leur réponse';
$string['cbx_updated_choice_notification'] = 'Lorsqu\'un étudiant modifie son choix';
$string['cbx_validated_choice_notification'] = 'Lorsqu\'un étudiant valide son choix';
$string['changeselectedbadges'] = 'Modifier les badges sélectionnés';
$string['changebadgeselectionsgroup'] = 'Modifier les badges sélectionnés';
$string['changebadgeselectionsgroup_help'] = 'Attention ! Cette opération va supprimer l\'ensemble des choix de badges de chaque utilisateur de ce parcours.';
$string['completionvalidatedbadges'] = 'Les étudiants doivent valider leurs choix de badges';
$string['course_badges'] = 'Badges de parcours';
$string['coursebadges:addinstance'] = 'Ajouter un nouveau choix de badges';
$string['coursebadges:choose'] = 'Enregistrer un choix';
$string['coursebadges:deletechoice'] = 'Supprimer les réponses';
$string['coursebadges:viewbadgesoverview'] = 'Consulter l\'aperçu des badges' ;
$string['coursebadges:viewusersoverview'] = 'Consulter l\'aperçu des étudiants';
$string['coursebadges_notification_task'] = 'Système de notification pour l\'activité Choix de badges' ;

$string['criteria'] = 'Critères';
$string['description'] = 'Description';

$string['email_updated_message_intro'] = '<p>Bonjour {$a->username}, <br/>
Vous avez reçu cette notification car l\'utilisateur <b>{$a->affectedusername}</b> a modifié ses choix de badges dans le parcours "{$a->coursename}" en choisissant les badges suivants : <br/></p>';
$string['email_validated_message_intro'] = '<p>Bonjour {$a->username}, <br/>
Vous avez reçu cette notification car l\'utilisateur <b>{$a->affectedusername}</b> a bien validé ses choix de badges dans le parcours "{$a->coursename}" avec les badges suivants : <br/></p>';
$string['email_message_outro'] = '<br/><br/>
<p>Vous pouvez consulter l\'activité concernée en cliquant sur le lien suivant : <a href="{$a->activitylink}">{$a->activityname}</a><br/>
Au plaisir de vous retrouver prochainement. <br/><br/></p>';

$string['error_configuration_activity_notif'] = 'Le formulaire n\'a pas pu être validé car la ou les conditions n\'ont pas été respectées : <ul>
{$a->ruleminallowbadge}
{$a->rulemaxallowbadge}
</ul>';
$string['error_rule_min_allow_badge'] = '<li>Le nombre de choix de badges doit être inférieur ou égal à {$a}.</li>';
$string['error_rule_max_allow_badge'] = '<li>Le nombre de choix de badges doit être supérieur ou égal à {$a}.</li>';

$string['dateformat'] = 'dd-mm-yy';

$string['label_activity_management'] = 'Réglages de l\'activité';
$string['label_badges_management'] = 'Gestion des badges';
$string['label_change_badge_selections'] = 'Modifier les badges sélectionnés';
$string['label_manage_badges'] = 'Gestion des badges';
$string['label_notification'] = 'Notification (par défaut : Pas de notification)';
$string['label_show_awarded_results'] = 'Publier les résultats (afficher les étudiants ayant sélectionnés des badges)';

$string['manage_badges_link'] = 'Gérer les badges du parcours';
$string['modulename'] = 'Choix de badges';
$string['modulename_help'] = 'Le module d\'activité choix de badges permet aux étudiants de se fixer des objectifs pour l\'obtention d\'un ou plusieurs badges.
L\'enseignant paramètre le ou les badges qui pourront être choisi par les étudiants parmi ceux disponibles dans le parcours.
Les étudiants peuvent suivre l\'obtention des badges qu\'ils ont retenus. Selon les paramétrages, ils ont aussi accès aux choix des autres étudiants.
Les enseignants ont accès à une vue synthétique des badges choisis et obtenus par les étudiants.
Selon le paramétrage, cette activité est associée à un bloc qui s\'affiche automatiquement dans les colonnes latérales du parcours.';
$string['modulenameplural'] = 'Choix de badges';

$string['nobadgeincourse'] = 'Aucun badge n\'existe dans ce parcours.';
$string['notification_add_choice'] = 'Le choix des badges a bien été effectué.';
$string['notification_delete_user_choice'] = 'Les choix de badges pour cette activité ont bien été supprimés.';
$string['notification_error_add_choice'] = 'Vous n\'avez pas les droits requis pour ajouter un choix de badges';
$string['notification_error_delete_choice'] = 'Vous n\'avez pas les droits requis pour supprimer un choix de badges';
$string['notification_updated_choice'] = 'Modification de badges sur l\'activité {$a}';
$string['notification_validated_choice'] = 'Validation de badges sur l\'activité {$a}';
$string['obtained_badges'] = 'Badges obtenus';
$string['pleasesetonebadgeor'] = 'Veuillez créer au moins un badge pour ce parcours.
<ul>
<li><a href="{$a->linkbadges}">Gérer les badges</a></li>
<li><a href="{$a->linkcourse}">Revenir au parcours</a></li>
</ul>';

$string['pluginadministration'] = 'Administration Choix de badges';
$string['pluginname'] = 'Choix de badges';

$string['pre_select_badges'] = 'Badges proposés';
$string['privacy:metadata:coursebadges_usr_select_bdg'] = 'Information à propos des badges sélectionnés dans l\'activité.';
$string['privacy:metadata:coursebadges_usr_select_bdg:userid'] = 'ID de l\'utilisateur qui a sélectionné le badge.';
$string['privacy:metadata:coursebadges_usr_select_bdg:selectionbadgeid'] = 'ID du badge sélectionné choisi par l\'utilisateur.';

$string['selected_badges'] = 'Badges sélectionnés';

$string['txt_badges_max_required'] = 'Nombre maximum de badges à sélectionner (par défaut : aucun)';
$string['txt_badges_min_required'] = 'Nombre minimum de badges à sélectionner (par défaut : aucun)';
$string['txt_course_badges'] = 'Nom de l\'activité';

$string['warning_configuration_activity_notif'] = 'Cette activité a été configurée de la manière suivante :
<ul>
{$a->ruleallowmodif}
{$a->ruleminallowbadge}
{$a->rulemaxallowbadge}
</ul>';
$string['warning_rule_allow_modif'] = '<li>Une fois les choix de badges validés, la modification du choix de badges est refusée.</li>';
$string['warning_rule_max_allow_badge'] = '<li>Le nombre maximum de choix de badges autorisé est de {$a}.</li>';
$string['warning_rule_min_allow_badge'] = '<li>Le nombre minimum de choix de badges autorisé est de {$a}.</li>';

/***********************/
/* Overview Traduction */
/***********************/

$string['badgefieldsearch'] = 'Badge';
$string['badgesoverviewlink'] = 'Aperçu des badges';
$string['badgeoverviewtitle'] = 'Aperçu des badges';
$string['descriptioncolumn'] = 'Description';
$string['earnedbadgescolumn'] = 'Badges obtenus';
$string['earnedbadgelabel'] = 'Obtenu';
$string['filtertitle'] = 'Aperçu global des badges';
$string['firstnamecolumn'] = 'Prénom de l\'étudiant';
$string['groupfieldsearch'] = 'Groupe';
$string['groupnamecolumn'] = 'Groupe';
$string['imagecolumn'] = 'Image';
$string['lastnamecolumn'] = 'Nom de l\'étudiant';
$string['namecolumn'] = 'Nom';
$string['modcolumn'] = 'Activité choix de badge';
$string['modnamefieldsearch'] = 'Activité choix de badge';
$string['nobadgeoverview'] = '<p>Vous ne pouvez pas entrer dans l\'aperçu des badges</p>
<p>Vous n\'avez pas les droits requis pour pouvoir consulter cette page.</p>';
$string['nouseroverview'] = '<p>Vous ne pouvez pas entrer dans l\'aperçu des étudiants</p>
<p>Vous n\'avez pas les droits requis pour pouvoir consulter cette page.</p>';
$string['usersoverviewlink'] = 'Aperçu des étudiants';
$string['useroverviewtitle'] = 'Aperçu des étudiants';
$string['percentcolumn'] = 'Atteinte des objectifs';
$string['ratiocolumn'] = 'Obtenu';
$string['selectedbadgescolumn'] = 'Badges sélectionnés';
$string['selectedbadgelabel'] = 'Sélectionné';
$string['statuslabel'] = 'Statut';
$string['usernamefield'] = 'Nom/Prénom';
