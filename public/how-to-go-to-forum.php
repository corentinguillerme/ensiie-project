<?php
session_start();
include './Vue.php'; ?>

<meta charset="UTF-8" content="width=device-width, initial-scale= 1.0">
<link rel="stylesheet" href="style_howTo.css">

<html>
    <head>
        <title>How to go to forum</title>
    </head>
   <?php en_tete(isset($_SESSION['connecte'])); ?>
        <h1>Démarche à suivre</h1>
        <p>
            <table>
                <tr>
                    <td class="invisible"></td>
                    <th>Région parisienne</th>
                    <th>France métropolitaine</th>
                    <th>Etranger et Outre-Mer</th>
                </tr>
                <tr>
                    <th>Inscription sur la liste des forums</th>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                </tr>
                    <th>Ordre de mission (à remplir le plus tôt possible)</th>
                    <td>X</td>
                    <td>X</td>
                    <td>X</td>
                </tr>
                    <th>Goodies (à retirer la veille ou quelques jours avant le forum)</th>
                    <td></td>
                    <td>X</td>
                    <td>X</td>
                </tr>    
                    <th>Copie de réservation du billet de train/avion ou trajet fourni par Mappy</th>
                    <td></td>
                    <td>X</td>
                    <td>X</td>
                </tr>
                    <th>Remboursement du trajet</th>
                    <td>Non</td>
                    <td>Total</td>
                    <td>Partiel ou total (à définir selon le coût total du trajet)</td>
                </tr>   
            </table>
        </p>
            <h2>Avant le forum :</h2>
        <p>
            <ul>
            <li>Inscrivez-vous sur la liste des forums</li>
            <li>Si votre forum ne figure pas sur la liste, indiquez-le nous et/ou à Stéphanie Roche pour que l'école contacte la prépa/IUT en question, 
                ou contactez vous-même le responsable du forum et par la suite Stéphanie Roche pour lui indiquer que l'école peut participer à ce forum.
            Si l'école ne s'est pas inscrite au préalable il ne sert à rien de se présenter à un forum.</li>
            <li>Renseignez-vous sur les formations, parcours, master bi-cursus et partenariats à l'étranger que l'école propose afin de pouvoir présenter 
                cela aux élèves. (Cf brochure des enseignements sur l'intranet)</li>
            <li>Envoyez la copie de réservation de billet ou le trajet fourni par Mappy à IImage pour le remboursement futur.</li>
            <li>Allez chercher votre ordre de mission auprès de Stéphanie Roche</li>
            <li>1 semaine avant votre forum, récupérez les goodies auprès de Stéphanie Roche</li>
            </ul>
            </p>
        <h2>Pendant le forum :</h2>
        <p> <ul>
            <li>Faites signer votre ordre de mission par le responsable du forum afin d'attester de votre présence</li>
            <li>Soyez souriants, ouverts aux questions, présentables, etc... (nous vous rappelons que vous faites un travail de communication).</li>
        </ul>    
        </p>
            <h2>
                Après le forum :</h2>
                <p>
                    <ul>
                    <li>Rendez les goodies non-distribués à Stéphanie Roche ainsi que votre ordre de mission signé par le responsable du forum</li>
                    <li>Vous recevrez le remboursement de votre trajet, si vous y avez droit, le plus tôt possible</li>
                    </ul>
                </p>
                <h2>Remarques :</h2>
                <p>
                    <ul>
                    <li>Ne vous inscrivez pas à plus de deux ou trois forums par élève (surtout s'ils ont lieu en semaine), ni à plus de deux ou trois élèves par forum 
                    (il ne sert à rien d'être trop nombreux sur un stand). Vous n'êtes pas obligés de vous inscrire au forum de votre prépa/DUT.</li>
                    <li>Si votre forum a lieu en semaine, l'ordre de mission vous assure la banalisation des cours pour cette journée uniquement. Cependant, si un 
                        partiel/TP noté a lieu le jour d'un forum où vous petes inscrit, vous n'aurez pas le droit de le manquer (auquel cas vous vous verrez attribuer 
                        la note de 0/20).
                        Renseignez-vous donc au préalable auprès des responsables d'UE afin de savoir si un partiel/TP noté est prévu le même jour qu'un forum.</li>
                    <li>Le justificatif de trajet (copie de réservation ou itinéraire Mappy) est à fournir avant votre départ et doit indiquer le prix et la date du 
                        trajet. SI le justificatif est donnée après le forum ou si ces informations manquent, alors vous ne serez pas remboursés</li>
                    <li>De manière générale, la procédure décrite ici doit être appliquée à la lettre afin de vous assurer d'avoir le droit de vous présenter à un 
                        forum, d'avoir vos journées de cours banalisées, d'être remboursés du trajet, etc...</li>
                    </ul>
                </p>
                <p>
                    Pour toute question supplémentaire, n'hésitez pas à vous adresser à un membre d'IImage ou à passer à l'IInédite<br/>
                    Bon courage à tous,<br/>
                    <strong>L'équipe d'IImage</strong>
                </p>
    </body>
</html>

<?php pied(); ?>