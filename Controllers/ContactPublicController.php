<?php

namespace CMW\Controller\Contact;

use CMW\Controller\Core\MailController;
use CMW\Controller\Core\SecurityController;
use CMW\Manager\Flash\Alert;
use CMW\Manager\Flash\Flash;
use CMW\Manager\Lang\LangManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Router\Link;
use CMW\Manager\Security\EncryptManager;
use CMW\Manager\Views\View;
use CMW\Model\Contact\ContactModel;
use CMW\Model\Contact\ContactSettingsModel;
use CMW\Utils\Redirect;
use CMW\Utils\Utils;
use CMW\Utils\Website;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class: @ContactPublicController
 * @package Contact
 * @author Teyir
 * @version 0.0.1
 */
class ContactPublicController extends AbstractController
{
    #[Link('/', Link::GET, [], '/contact')]
    private function publicContact(): void
    {
        View::createPublicView('Contact', 'main')->view();
    }

    #[NoReturn]
    #[Link('/', Link::POST, [], '/contact')]
    private function publicContactPost(): void
    {
        [$email, $name, $object, $content] = Utils::filterInput('email', 'name', 'object', 'content');

        $encryptedMail = EncryptManager::encrypt($email);
        $encryptedName = EncryptManager::encrypt($name);
        $encryptedObject = EncryptManager::encrypt($object);
        $encryptedContent = EncryptManager::encrypt($content);

        if (ContactSettingsModel::getInstance()->getConfig()->getAntiSpamActive()) {
            if ($this->is_blacklisted_email($email)) {
                contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
            if ($this->contains_blacklisted_word($email)) {
                contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
            if ($this->contains_blacklisted_word($name)) {
                contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
            if ($this->contains_blacklisted_word($object)) {
                contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
            if ($this->contains_blacklisted_word($content)) {
                contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 1);
                Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'), LangManager::translate('contact.toaster.send.success'));
                Redirect::redirectPreviousRoute();
            }
        }

        $config = contactSettingsModel::getInstance()->getConfig();

        if ($config === null || $config->getEmail() === null) {
            Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.error.notConfigured'));
            Redirect::redirectPreviousRoute();
        }
        if (SecurityController::checkCaptcha()) {
            if (Utils::containsNullValue($email, $name, $object, $content)) {
                Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                    LangManager::translate('contact.toaster.send.errorFillFields'));
                Redirect::redirectPreviousRoute();
            }

            contactModel::getInstance()->addMessage($encryptedMail, $encryptedName, $encryptedObject, $encryptedContent, 0);

            MailController::getInstance()->sendMail($email, $config->getObjectConfirmation(), $config->getMailConfirmation());
            MailController::getInstance()->sendMail($config->getEmail(), '[' . Website::getWebsiteName() . ']' . LangManager::translate('contact.mail.object'), LangManager::translate('contact.mail.mail') . $email . LangManager::translate('contact.mail.name') . $name . LangManager::translate('contact.mail.object_sender') . $object . LangManager::translate('contact.mail.content') . $content);

            Flash::send(Alert::SUCCESS, LangManager::translate('core.toaster.success'),
                LangManager::translate('contact.toaster.send.success'));
        } else {
            Flash::send(Alert::ERROR, LangManager::translate('core.toaster.error'),
                LangManager::translate('contact.toaster.send.error-captcha'));
        }
        Redirect::redirectPreviousRoute();
    }

    private function contains_blacklisted_word($input)
    {
        foreach ($this->blacklisted_words as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }

    private function is_blacklisted_email($email): bool
    {
        $domain = substr(strrchr($email, '@'), 1);
        return in_array($domain, $this->blacklisted_domains);
    }

    private array $blacklisted_words = [
        'porn', 'porno', 'sex', 'sexy', 'xxx', 'adult', 'erotic', 'nude', 'nudity', 'hentai',
        'escort', 'camgirl', 'camsex', 'cams', 'anal', 'barelylegal', 'bdsm', 'bestiality', 'bimbo',
        'blowjob', 'boobs', 'booty', 'bukkake', 'butt', 'chaturbate', 'cocks', 'creampie', 'cunnilingus',
        'cum', 'cumshot', 'deepthroat', 'dildo', 'doggystyle', 'domination', 'dominatrix', 'ejaculation',
        'facial', 'femdom', 'fisting', 'footjob', 'gangbang', 'handjob', 'hardcore', 'hustler', 'incest',
        'jerkoff', 'lesbian', 'lust', 'milf', 'orgasm', 'orgy', 'panties', 'pegging', 'penetration',
        'penis', 'pissing', 'playboy', 'pornhub', 'porno', 'pornstar', 'prostitute', 'pussy', 'rape',
        'rimming', 'shemale', 'slut', 'spanking', 'strip', 'stripper', 'suck', 'swinger', 'tits',
        'twink', 'upskirt', 'vagina', 'voyeur', 'whore', 'xhamster', 'xvideos', 'youporn', 'zoophilia',
        'fetish', 'kink', 'bdsm', 'bondage', 'submissive', 'dominant', 's&m', 'masterbation', 'masturbate',
        'sexting', 'cam', 'camsex', 'livecam', 'erotica', 'adultfriendfinder', '3some', '4some', 'arousal',
        'ass', 'asshole', 'ballgag', 'bangbros', 'bbw', 'beaver', 'beeg', 'bj', 'bondage', 'boner',
        'brazzers', 'bukakke', 'bush', 'clit', 'clitoris', 'cock', 'coitus', 'cunt', 'dick', 'dildo',
        'dogging', 'doggystyle', 'ejaculate', 'fap', 'fingering', 'fleshlight', 'freaky', 'fuck', 'gangbang',
        'handjob', 'hentai', 'hustler', 'jizz', 'kamasutra', 'knockers', 'lingerie', 'mature', 'nipple',
        'nipples', 'nude', 'nudity', 'orgy', 'panty', 'pornographic', 'pornography', 'queef', 'quicky',
        'randy', 'rimming', 'sexcam', 'shaved', 'smut', 'snatch', 'spank', 'tit', 'topless',
        'vajayjay', 'voyeur', 'wank', 'x-rated', 'xxx', 'youporn', 'zoophilia', 'zooporn', 'price', 'sell', 'buy',
        'spam', 'spamm', 'prices', 'site', 'website', 'domain', 'reseller', 'SEO', 'date', 'dating', 'Rewards', 'rewards',
        'improve', 'website`s', 'casino'
    ];

    private array $blacklisted_domains = [
        '0-mail.com',
        '0815.ru',
        '0clickemail.com',
        '10minutemail.com',
        '20minutemail.com',
        '2prong.com',
        '30minutemail.com',
        '33mail.com',
        '3mail.ga',
        '4mail.cf',
        '4mail.ga',
        '4mail.ml',
        '5mail.cf',
        '5mail.ga',
        '5mail.ml',
        '6mail.cf',
        '6mail.ga',
        '6mail.ml',
        '7mail.ga',
        '7mail.ml',
        '9mail.cf',
        '9mail.ga',
        '9mail.ml',
        'a-bc.net',
        'a45.in',
        'a54pd15c.com',
        'a-bc.net',
        'anonbox.net',
        'anonymbox.com',
        'antichef.net',
        'antispam.de',
        'baxomale.ht.cx',
        'beefmilk.com',
        'binkmail.com',
        'bio-muesli.net',
        'bobmail.info',
        'bodhi.lawlita.com',
        'bofthew.com',
        'boun.cr',
        'bouncr.com',
        'breakthru.com',
        'bsnow.net',
        'bugmenot.com',
        'bumpymail.com',
        'casualdx.com',
        'centermail.com',
        'centermail.net',
        'chogmail.com',
        'clrmail.com',
        'cmail.net',
        'consumerriot.com',
        'courriel.fr.nf',
        'courrieltemporaire.com',
        'curryworld.de',
        'dacoolest.com',
        'dandikmail.com',
        'dayrep.com',
        'dcemail.com',
        'deadaddress.com',
        'deadspam.com',
        'despam.it',
        'despammed.com',
        'devnullmail.com',
        'dfgh.net',
        'digitalsanctuary.com',
        'discardmail.com',
        'discardmail.de',
        'disposableaddress.com',
        'disposableemailaddresses.com',
        'disposableemailaddresses:emailmiser.com',
        'disposeamail.com',
        'dispostable.com',
        'dm.w3internet.co.uk',
        'dodgeit.com',
        'dodgit.com',
        'dodgit.org',
        'dontreg.com',
        'dontsendmespam.de',
        'dump-email.info',
        'dumpandjunk.com',
        'dumpmail.de',
        'dumpyemail.com',
        'e4ward.com',
        'email60.com',
        'emaildienst.de',
        'emailias.com',
        'emailigo.de',
        'emailinfive.com',
        'emailmiser.com',
        'emailsensei.com',
        'emailtemporanea.net',
        'emailtemporario.com.br',
        'emailthe.net',
        'emailtmp.com',
        'emailto.de',
        'emailwarden.com',
        'emailx.at.hm',
        'emailxfer.com',
        'emeil.in',
        'emeil.ir',
        'emz.net',
        'enterto.com',
        'ephemail.net',
        'etranquil.com',
        'etranquil.net',
        'etranquil.org',
        'evopo.com',
        'explodemail.com',
        'express.net.ua',
        'eyepaste.com',
        'fakeinbox.com',
        'fakeinformation.com',
        'fansworldwide.de',
        'fantasymail.de',
        'fastacura.com',
        'fastchevy.com',
        'fastchrysler.com',
        'fastkawasaki.com',
        'fastmazda.com',
        'fastmitsubishi.com',
        'fastnissan.com',
        'fastsubaru.com',
        'fastsuzuki.com',
        'fasttoyota.com',
        'fastyamaha.com',
        'fightallspam.com',
        'fizmail.com',
        'fleckens.hu',
        'fr33mail.info',
        'frapmail.com',
        'front14.org',
        'fudgerub.com',
        'fux0ringduh.com',
        'garliclife.com',
        'gehensiemirnichtaufdensack.de',
        'get1mail.com',
        'get2mail.fr',
        'getairmail.com',
        'getmails.eu',
        'getonemail.com',
        'getonemail.net',
        'girlsundertheinfluence.com',
        'gishpuppy.com',
        'gmial.com',
        'goemailgo.com',
        'gotmail.net',
        'gotmail.org',
        'gotti.otherinbox.com',
        'great-host.in',
        'greensloth.com',
        'grr.la',
        'gsrv.co.uk',
        'guerillamail.biz',
        'guerillamail.com',
        'guerillamail.net',
        'guerillamail.org',
        'guerrillamail.biz',
        'guerrillamail.com',
        'guerrillamail.de',
        'guerrillamail.info',
        'guerrillamail.net',
        'guerrillamail.org',
        'guerrillamailblock.com',
        'haltospam.com',
        'hatespam.org',
        'herp.in',
        'hidemail.de',
        'hidzz.com',
        'hochsitze.com',
        'hopemail.biz',
        'ieh-mail.de',
        'imap.cc',
        'inbax.tk',
        'inbox.si',
        'inboxalias.com',
        'inboxclean.com',
        'inboxclean.org',
        'infocom.zp.ua',
        'instant-mail.de',
        'ipoo.org',
        'irish2me.com',
        'iwi.net',
        'jetable.com',
        'jetable.fr.nf',
        'jetable.net',
        'jetable.org',
        'jnxjn.com',
        'jourrapide.com',
        'jsrsolutions.com',
        'kasmail.com',
        'kaspop.com',
        'killmail.com',
        'killmail.net',
        'kir.ch.tc',
        'klassmaster.com',
        'klassmaster.net',
        'klzlk.com',
        'koszmail.pl',
        'kurzepost.de',
        'lifebyfood.com',
        'link2mail.net',
        'litedrop.com',
        'lol.ovpn.to',
        'lookugly.com',
        'lopl.co.cc',
        'lortemail.dk',
        'lr7.us',
        'm4ilweb.info',
        'mail-filter.com',
        'mail-temporaire.fr',
        'mail.by',
        'mail.mezimages.net',
        'mail.zp.ua',
        'mail1a.de',
        'mail21.cc',
        'mail2rss.org',
        'mail333.com',
        'mailbidon.com',
        'mailblocks.com',
        'mailbucket.org',
        'mailcat.biz',
        'mailcatch.com',
        'mailde.de',
        'mailde.info',
        'maildrop.cc',
        'maileater.com',
        'mailed.in',
        'mailexpire.com',
        'mailfa.tk',
        'mailforspam.com',
        'mailfreeonline.com',
        'mailguard.me',
        'mailin8r.com',
        'mailinater.com',
        'mailinator.co.uk',
        'mailinator.com',
        'mailinator.net',
        'mailinator.org',
        'mailinator.us',
        'mailinator2.com',
        'mailincubator.com',
        'mailismagic.com',
        'mailme.lv',
        'mailme24.com',
        'mailmetrash.com',
        'mailmoat.com',
        'mailms.com',
        'mailna.biz',
        'mailna.co',
        'mailna.in',
        'mailnesia.com',
        'mailnull.com',
        'mailorc.com',
        'mailorg.org',
        'mailpick.biz',
        'mailrock.biz',
        'mailscrap.com',
        'mailshell.com',
        'mailsiphon.com',
        'mailslapping.com',
        'mailtemp.info',
        'mailtothis.com',
        'mailzilla.com',
        'mailzilla.org',
        'mailzilla.orgmbx.cc',
        'makemetheking.com',
        'mbx.cc',
        'mega.zik.dj',
        'meinspamschutz.de',
        'meltmail.com',
        'messagebeamer.de',
        'mezimages.net',
        'ministry-of-silly-walks.de',
        'mintemail.com',
        'misterpinball.de',
        'moncourrier.fr.nf',
        'monemail.fr.nf',
        'monmail.fr.nf',
        'msa.minsmail.com',
        'mt2009.com',
        'mt2014.com',
        'mx0.wwwnew.eu',
        'mycleaninbox.net',
        'mytrashmail.com',
        'neomailbox.com',
        'nepwk.com',
        'nervmich.net',
        'nervtmich.net',
        'netmails.net',
        'netmails.org',
        'neverbox.com',
        'nice-4u.com',
        'nincsmail.hu',
        'no-spam.ws',
        'nobulk.com',
        'nobuma.com',
        'noclickemail.com',
        'nodezine.com',
        'nogmailspam.info',
        'nomail.xl.cx',
        'nomail2me.com',
        'nomorespamemails.com',
        'nonspammer.de',
        'nospam.wins.com.br',
        'nospam.ze.tc',
        'nospam4.us',
        'nospamfor.us',
        'nospammail.net',
        'nospamthanks.info',
        'notmailinator.com',
        'notsharingmy.info',
        'nowhere.org',
        'nowmymail.com',
        'ntlhelp.net',
        'nullbox.info',
        'objectmail.com',
        'obobbo.com',
        'oneoffemail.com',
        'onewaymail.com',
        'onlatedotcom.info',
        'online.ms',
        'oopi.org',
        'opayq.com',
        'ordinaryamerican.net',
        'otherinbox.com',
        'ovpn.to',
        'owlpic.com',
        'pancakemail.com',
        'pepbot.com',
        'pfui.ru',
        'plexolan.de',
        'poczta.onet.pl',
        'politikerclub.de',
        'pookmail.com',
        'privacy.net',
        'privatdemail.net',
        'proxymail.eu',
        'prtnx.com',
        'putthisinyourspamdatabase.com',
        'qq.com',
        'quickinbox.com',
        'rcpt.at',
        'reallymymail.com',
        'recode.me',
        'recursor.net',
        'reliable-mail.com',
        'rhyta.com',
        'rmqkr.net',
        'royal.net',
        'rppkn.com',
        'rtrtr.com',
        'safe-mail.net',
        'safersignup.de',
        'safetymail.info',
        'safetypost.de',
        'saynotospams.com',
        'selfdestructingmail.com',
        'sendspamhere.com',
        'sharklasers.com',
        'shieldedmail.com',
        'shiftmail.com',
        'shitmail.me',
        'shitware.nl',
        'shortmail.net',
        'sibmail.com',
        'sinnlos-mail.de',
        'siteposter.net',
        'skeefmail.com',
        'slapsfromlastnight.com',
        'slaskpost.se',
        'slave-auctions.net',
        'slopsbox.com',
        'smellfear.com',
        'snakemail.com',
        'sneakemail.com',
        'sneakmail.de',
        'snkmail.com',
        'sofimail.com',
        'sofort-mail.de',
        'sogetthis.com',
        'soodonims.com',
        'spam.la',
        'spam.su',
        'spam4.me',
        'spamail.de',
        'spamarrest.com',
        'spambob.com',
        'spambob.net',
        'spambob.org',
        'spambog.com',
        'spambog.de',
        'spambog.ru',
        'spambooger.com',
        'spambox.info',
        'spambox.us',
        'spamcannon.com',
        'spamcannon.net',
        'spamcon.org',
        'spamcorptastic.com',
        'spamcowboy.com',
        'spamcowboy.net',
        'spamcowboy.org',
        'spamday.com',
        'spamdecoy.net',
        'spamdel.com',
        'spamdonkey.com',
        'spamfree.eu',
        'spamfree24.com',
        'spamfree24.de',
        'spamfree24.eu',
        'spamfree24.info',
        'spamfree24.net',
        'spamfree24.org',
        'spamgoes.in',
        'spamgourmet.com',
        'spamgourmet.net',
        'spamgourmet.org',
        'spamherelots.com',
        'spamhereplease.com',
        'spamhole.com',
        'spamify.com',
        'spaml.com',
        'spaml.de',
        'spamlot.net',
        'spamluv.com',
        'spammotel.com',
        'spamobox.com',
        'spamoff.de',
        'spamslicer.com',
        'spamspot.com',
        'spamstack.net',
        'spamthis.co.uk',
        'spamthisplease.com',
        'spamtrail.com',
        'spamtroll.net',
        'speed.1s.fr',
        'spoofmail.de',
        'stuffmail.de',
        'suremail.info',
        'talkinator.com',
        'teewars.org',
        'teleworm.com',
        'teleworm.us',
        'temp-mail.org',
        'temp-mail.ru',
        'tempalias.com',
        'tempe-mail.com',
        'tempemail.co.za',
        'tempemail.com',
        'tempemail.net',
        'tempinbox.co.uk',
        'tempinbox.com',
        'tempmail.eu',
        'tempmail.it',
        'tempmail2.com',
        'tempmaildemo.com',
        'tempmailer.com',
        'tempmailer.de',
        'tempomail.fr',
        'temporarily.de',
        'temporarioemail.com.br',
        'temporaryemail.net',
        'temporaryforwarding.com',
        'temporaryinbox.com',
        'temporarymailaddress.com',
        'tempthe.net',
        'thankyou2010.com',
        'thc.st',
        'thelimestones.com',
        'thisisnotmyrealemail.com',
        'thismail.net',
        'throwawayemailaddress.com',
        'throwawaymail.com',
        'tilien.com',
        'tittbit.in',
        'tizi.com',
        'tmailinator.com',
        'toomail.biz',
        'top100mail.com',
        'topranklist.de',
        'tradermail.info',
        'trash-mail.at',
        'trash-mail.com',
        'trash-mail.de',
        'trash2009.com',
        'trash2010.com',
        'trash-amil.com',
        'trashbox.eu',
        'trashdevil.com',
        'trashdevil.de',
        'trashemail.de',
        'trashmail.at',
        'trashmail.com',
        'trashmail.de',
        'trashmail.me',
        'trashmail.net',
        'trashmail.org',
        'trashmail.ws',
        'trashmailer.com',
        'trashymail.com',
        'trashymail.net',
        'trayna.com',
        'trbvm.com',
        'trialmail.de',
        'trillianpro.com',
        'tryalert.com',
        'turual.com',
        'twinmail.de',
        'tyldd.com',
        'uggsrock.com',
        'umail.net',
        'unmail.ru',
        'upliftnow.com',
        'uplipht.com',
        'uroid.com',
        'us.af',
        'venompen.com',
        'veryrealemail.com',
        'viditag.com',
        'viewcastmedia.com',
        'viewcastmedia.net',
        'viewcastmedia.org',
        'viralplays.com',
        'vpn.st',
        'vsimcard.com',
        'vubby.com',
        'vztc.com',
        'wasteland.rfc822.org',
        'webemail.me',
        'webm4il.info',
        'webuser.in',
        'wee.my',
        'wefjo.grn.cc',
        'weg-werf-email.de',
        'wegwerf-email-addressen.de',
        'wegwerf-emails.de',
        'wegwerfadresse.de',
        'wegwerfemail.de',
        'wegwerfmail.de',
        'wegwerfmail.info',
        'wegwerfmail.net',
        'wegwerfmail.org',
        'welikecookies.com',
        'wh4f.org',
        'whyspam.me',
        'willhackforfood.biz',
        'willselfdestruct.com',
        'winemaven.info',
        'wronghead.com',
        'wuzup.net',
        'wuzupmail.net',
        'wwwnew.eu',
        'x.ip6.li',
        'xagloo.com',
        'xemaps.com',
        'xents.com',
        'xmaily.com',
        'xoxy.net',
        'yep.it',
        'yogamaven.com',
        'yopmail.com',
        'yopmail.fr',
        'yopmail.net',
        'yourdomain.com',
        'ypmail.webarnak.fr.eu.org',
        'yuurok.com',
        'zehnminuten.de',
        'zehnminutenmail.de',
        'zippymail.info',
        'zoemail.net',
        'zoemail.org',
        'zomg.info',
        'zumail.net',
        'zxcv.com',
        'zzz.com',
    ];
}
