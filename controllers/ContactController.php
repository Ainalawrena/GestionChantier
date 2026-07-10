<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class ContactController {

    public function envoyer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=home');
            exit;
        }

        $nom      = htmlspecialchars(trim($_POST['nom']     ?? ''));
        $societe  = htmlspecialchars(trim($_POST['societe'] ?? ''));
        $email    = htmlspecialchars(trim($_POST['email']   ?? ''));
        $tel      = htmlspecialchars(trim($_POST['tel']     ?? ''));
        $sujet    = htmlspecialchars(trim($_POST['sujet']   ?? ''));
        $message  = htmlspecialchars(trim($_POST['message'] ?? ''));

        if (empty($nom) || empty($email) || empty($message)) {
            header('Location: contact.php?erreur=champs_vides');
            exit;
        }

        $mail = new PHPMailer(true);

        try {
            // Config SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'nyaina.lawrena@gmail.com'; // ← ton gmail
            $mail->Password   = 'npoj muio vtqy ppqx';      // ← mot de passe app Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Expéditeur et destinataire
            $mail->setFrom('nyaina.lawrena@gmail.com', 'Construct IT');
            $mail->addAddress('nyaina.lawrena@gmail.com', 'Aina');
            $mail->addReplyTo($email, $nom);

            // Contenu email
            $mail->isHTML(true);
            $mail->Subject = "Construct IT — $sujet — $nom";
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: #f97316; padding: 20px; border-radius: 10px 10px 0 0;'>
                        <h2 style='color: white; margin: 0;'>📩 Nouveau message — Construct IT</h2>
                    </div>
                    <div style='background: #f9f9f9; padding: 24px; border-radius: 0 0 10px 10px;'>
                        <table style='width:100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 8px 0; font-weight: bold; color: #555; width: 120px;'>Nom</td>
                                <td style='padding: 8px 0;'>$nom</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; font-weight: bold; color: #555;'>Société</td>
                                <td style='padding: 8px 0;'>$societe</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; font-weight: bold; color: #555;'>Email</td>
                                <td style='padding: 8px 0;'><a href='mailto:$email'>$email</a></td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; font-weight: bold; color: #555;'>Téléphone</td>
                                <td style='padding: 8px 0;'>$tel</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; font-weight: bold; color: #555;'>Sujet</td>
                                <td style='padding: 8px 0;'>$sujet</td>
                            </tr>
                        </table>
                        <hr style='margin: 16px 0; border-color: #ddd;'>
                        <h3 style='color: #333;'>Message</h3>
                        <p style='color: #555; line-height: 1.7;'>$message</p>
                    </div>
                    <p style='text-align:center; color:#aaa; font-size:0.8rem; margin-top:16px;'>
                        Envoyé depuis le formulaire de contact Construct IT
                    </p>
                </div>
            ";

            $mail->send();
            header('Location: contact.php?succes=1');
            exit;

        } catch (Exception $e) {
            header('Location: contact.php?erreur=envoi_echoue');
            exit;
        }
    }
}