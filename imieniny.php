<?php
/*
Plugin Name: Imieniny widget
Description: Adds a widget to provide the various Saint's namedays observed in Poland and throughout Polonia.
Author: Dcn. James Konicki
Version: alpha 0.4
Author URI: http://konicki.com/blog2/
Plugin URI: http://konicki.com/blog2/downloads/
Based on the Imieniny PHP script, version 1.4 by Adam 'asadi' Brucki
E-mail: asadi@asadi.prv.pl
WWW: http://www.asadi.prv.pl

Imieniny is released under the GNU General Public License (GPL)
WWW: http://www.gnu.org/licenses/gpl.txt
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.

function widget_imieniny_init() {

	// Check for the required plugin functions. This will prevent fatal
	// errors occurring when you deactivate the dynamic-sidebar plugin.

	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

// Options form for the widget - called in the Sidebar Widgets of the Presentation tab


	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.

	function widget_imieniny_control() {


        // Collect our widget's options.

        $options = $newoptions = get_option('widget_imieniny');

        // This is for handing the control form submission.

	if ( !is_array($newoptions) )
		$newoptions = array(
			'title'=>'Imieniny obchodzą:',
			'tdiff'=>'0');
        if ( $_POST['imieniny-submit'] ) {
            // Clean up control form submission options
            $newoptions['title'] = strip_tags(stripslashes($_POST['imieniny-title']));
	    $newoptions['tdiff'] = strip_tags(stripslashes($_POST['imieniny-tdiff'])); 
            }

        // If original widget options do not match control form
        // submission options, update them.

        if ( $options != $newoptions ) {
            $options = $newoptions;
            update_option('widget_imieniny', $options);
        }

        // Format options as valid HTML. Hey, why not.
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
	$tdiff = htmlspecialchars($options['tdiff'], ENT_QUOTES); 

	// The control form for editing options.        

        echo '<div>';
        echo '<label for="imieniny-title" style="line-height:35px;display:block;">' . _('Widget title:') . ' <input type="text" id="imieniny-title" name="imieniny-title" value="'.$title.'" /></label>';
        echo '<label for="imieniny-tdiff" style="line-height:35px;display:block;">' . _('Time differential (e.g., -5, +3, etc.):') . ' <input type="tdiff" id="imieniny-tdiff" name="imieniny-tdiff" value="'.$tdiff.'" /></label>';
        echo '<input type="hidden" name="imieniny-submit" id="imieniny-submit" value="1" />';
        echo '<div>';

    // end of widget_imieniny_control()

    } 

// This is the function that outputs our Imieniny form.

	function widget_imieniny($args) {
		
		// $args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys. Default tags: li and h2.

		extract($args);

// These are the widget functions and arrays.  Note, when editing this save in UTF-8 format or you will loose the diacritics
		$options = get_option('widget_imieniny');
		$title = empty($options['title']) ? 'Imieniny obchodzą:' : $options['title'];
		$tdiff = $options['tdiff'];
		$dzien = array( 0=> "Niedziela",
						1=> "Poniedziałek",
						2=> "Wtorek",
						3=> "Środa",
						4=> "Czwartek",
						5=> "Piątek",
						6=> "Sobota");

$miesiac = array( 1 => "Stycznia",
						   2 => "Lutego",
						   3 => "Marca",
						   4 => "Kwietnia",
						   5 => "Maja",
						   6 => "Czerwca",
						   7 => "Lipca",
						   8 => "Sierpnia",
						   9 => "Września",
						 10 => "Października",
						 11 => "Listopada",
						 12 => "Grudnia");

$imieniny = array( "1-1" => 'Mieczysław, Masław,<BR> Mieczysława, Mieszko',
							"1-2" => 'Abel, Izydor, Makary,<BR> Odil, Strzeżysław',
							"1-3" => 'Arletta, Danuta, Dan, Danisz, <BR>Enoch, Genowefa, Piotr, Włościsława',
							"1-4" => 'Angelika, Aniela, Benedykta, Benita, Dobromir, Dobrymir, Eugeniusz, Grzegorz, Izabela, Leonia, Rygobert, Tytus',
							"1-5" => 'Edward, Emilian, Emiliusz, Hanna, Symeon, Szymon, Telesfor, Włościbor',
							"1-6" => 'Andrzej, Baltazar, Balcer, Bolemir, Epifania, Kacper, Kasper, Melchior',
							"1-7" => 'Chociesław, Izydor, Julian, Lucjan, Walenty',
							"1-8" => 'Erhard, Mścisław, Seweryn',
							"1-9" => 'Antoni, Bazylissa, Borzymir, Julian, Julianna, Marcelina, Marcjanna',
							"1-10" => 'Agaton, Dobrosław, Jan, Nikanor, Paweł, Wilhelm',
							"1-11" => 'Feliks, Hilary, Honorata, Hygin, Krzesimir, Matylda, Mechtylda',
							"1-12" => 'Antoni, Arkadiusz, Arkady, Benedykt, Czesław, Czech, Czechasz, Czechoñ, Czesława, Ernest, Ernestyn, Greta, Reinhold, Tycjan',
							"1-13" => 'Bogumił, Bogusąd, Bogusława, Gotfryd, Godfryd, Leoncjusz, Melania, Weronika',
							"1-14" => 'Feliks, Hilary, Odo, Radogost',
							"1-15" => 'Aleksander, Dobrawa, Dąbrówka, Domasław, Domosław, Izydor, Makary, Maur, Paweł',
							"1-16" => 'Marcel, Waleriusz, Włodzimierz',
							"1-17" => 'Antoni, Jan, Rościsław',
							"1-18" => 'Bogumił, Jaropełk, Krystyna, Liberata, Małgorzata, Piotr, Pryska',
							"1-19" => 'Andrzej, Bernard, Erwin, Erwina, Eufemia, Henryk, Kanut, Mariusz, Marta, Matylda, Mechtylda, Pia, Racimir, Sara',
							"1-20" => 'Dobiegniew, Fabian, Sebastian',
							"1-21" => 'Agnieszka, Epifani, Jarosław, Jarosława, Jerosława, Marcela',
							"1-22" => 'Anastazy, Dobromysł, Gaudencjusz, Gaudenty, Marta, Wincenty',
							"1-23" => 'Emerencja, Ildefons, Jan, Klemens, Maria, Rajmund, Rajmunda, Wrócisława',
							"1-24" => 'Chwalibóg, Felicja, Mirogniew, Rafał, Rafęla, Tymoteusz',
							"1-25" => 'Miłosz, Miłowan, Miłowit, Paweł, Tatiana, Tacjanna',
							"1-26" => 'Paula, Paulina, Polikarp, Skarbimir, Wanda',
							"1-27" => 'Angelika, Ilona, Jan Chryzostom, Julian, Przybysław',
							"1-28" => 'Agnieszka, Augustyn, Flawian, Ildefons, Julian, Karol, Leonidas, Piotr, Radomir, Roger, Waleriusz',
							"1-29" => 'Franciszek Salezy, Gilda, Hanna, Walerian, Waleriana, Waleriusz, Zdzisław',
							"1-30" => 'Adelajda, Feliks, Gerard, Gerarda, Gerhard, Hiacynta, Maciej, Marcin, Martyna, Sebastian',
							"1-31" => 'Cyrus, Euzebiusz, Jan, Ksawery, Ludwik, Marceli, Marcelin, Marcelina, Piotr, Spycigniew, Wirgiliusz',
							"2-1" => 'Brygida, Bryda, Dobrocha, Dobrochna, Ignacy, Iga, Ignacja, Żegota, Paweł, Siemirad',
							"2-2" => 'Joanna, Korneliusz, Maria, Miłosława',
							"2-3" => 'Błażej, Hipolit, Hipolita, Laurencjusz, Wawrzyniec, Maksym, Oskar, Stefan, Telimena, Uniemysł',
							"2-4" => 'Andrzej, Gilbert, Jan, Joanna, Józef, Mariusz, Weronika, Witosława',
							"2-5" => 'Adelajda, Agata, Aga, Albin, Izydor, Jakub, Jan, Justynian, Paweł, Piotr, Strzeżysława',
							"2-6" => 'Angel, Angelus, Antoni, Bogdana, Bohdan, Bohdana, Dorota, Ksenia, Szymon, Tytus',
							"2-7" => 'Romuald, Ryszard, Sulisław',
							"2-8" => 'Gniewomir, Gniewosz, Honorat, Jan, Ksenofont, Lucjusz, Paweł, Piotr, Salomon, Sebastian, Żaklina',
							"2-9" => 'Apolonia, Bernard, Cyryl, Eryk, Eryka, Gorzysław, Mariusz, Nikifor, Rajnold',
							"2-10" => 'Elwira, Gabriel, Jacek, Jacenty, Scholastyka, Tomisława',
							"2-11" => 'Adolf, Adolfa, Adolfina, Alf, Bernadetta, Dezydery, Eufrozyna, Lucjan, Łazarz, Maria, Olgierd, Świętomira',
							"2-12" => 'Aleksy, Benedykt, Eulalia, Julian, Laurenty, Modest, Nora, Radzim, Trzebisława',
							"2-13" => 'Benigna, Grzegorz, Jordan, Kastor, Katarzyna, Klemens, Toligniew',
							"2-14" => 'Adolf, Adolfa, Adolfina, Alf, Cyryl, Dobiesława, Dobisława, Józef, Józefa, Konrad, Konrada, Krystyna, Liliana, Lilian, Mikołaj, Niemir, Niemira, Walenty, Zenon, Zenona',
							"2-15" => 'Faustyn, Georgia, Georgina, Jordan, Jowita, Józef, Klaudiusz, Przybyrad, Sewer',
							"2-16" => 'Bernard, Danuta, Dan, Danisz, Julianna, Symeon',
							"2-17" => 'Donat, Donata, Franciszek, Izydor, Julian, Konstanty, Łukasz, Niegomir, Sylwin, Zbigniew, Zbyszko',
							"2-18" => 'Albert, Alberta, Albertyna, Fryda, Konstancja, Krystiana, Maksym, Sawa, Sylwan, Sylwana, Symeon, Więcesława, Zuzanna, Zula',
							"2-19" => 'Arnold, Arnolf, Bądzisława, Gabin, Henryk, Konrad, Konrada, Leoncjusz, Manswet, Marceli',
							"2-20" => 'Euchariusz, Eustachy, Eustachiusz, Leon, Ludmiła, Ludomiła, Ostap, Siestrzewit',
							"2-21" => 'Eleonora, Feliks, Fortunat, Kiejstut, Teodor, Wyszeniega',
							"2-22" => 'Małgorzata, Nikifor, Piotr, Wiktor, Wiktoriusz, Wrócisław',
							"2-23" => 'Bądzimir, Damian, Florentyn, Łazarz, Piotr, Romana, Roma, Seweryn',
							"2-24" => 'Bogurad, Bogusz, Boguta, Bohusz, Lucjusz, Maciej, Piotr',
							"2-25" => 'Bolebor, Cezary, Konstancjusz, Maciej, Małgorzata, Modest, Nicefor',
							"2-26" => 'Aleksander, Bogumił, Cezariusz, Dionizy, Mirosław, Nestor',
							"2-27" => 'Aleksander, Anastazja, Auksencjusz, Gabriel, Gabriela, Honoryna, Leander, Leonard,  Sierosława',
							"2-28" => 'Chwalibóg, Józef, Makary, Nadbor, Roman',
							"2-29" => 'Dobronieg, Roman',
							"3-1" => 'Albin, Antoni, Antonina, Budzisław, Budzisz, Eudoksja, Eudokia, Ewdokia, Jewdocha, Feliks, Herakles, Herkules, Joanna, Józef, Nikifor, Piotr',
							"3-2" => 'Absalon, Franciszek, Halszka, Helena, Henryk, Januaria, Krzysztof, Lew, Michał, Paweł, Piotr, Radosław, Symplicjusz',
							"3-3" => 'Asteriusz, Hieronim, Kunegunda, Lucjola, Maryna, Wierzchosława',
							"3-4" => 'Adrian, Adrianna, Arkadiusz, Arkady, Eugeniusz, Kazimierz, Lew, Lucja, Lucjusz, Łucja, Wacław, Wacława',
							"3-5" => 'Adrian, Adrianna, Fryderyk, Jan, Pakosław, Pakosz, Wacław, Wacława',
							"3-6" => 'Eugenia, Felicyta, Frydolin, Jordan, Klaudian, Koleta, Róża, Wiktor, Wiktoriusz, Wojsław',
							"3-7" => 'Felicja, Nadmir, Paweł, Polikarp, Tomasz',
							"3-8" => 'Beata, Filemon, Jan, Julian, Miłogost, Miligost, Stefan, Wincenty',
							"3-9" => 'Apollo, Dominik, Franciszka, Katarzyna, Mścisława, Prudencjusz, Taras',
							"3-10" => 'Aleksander, Bożysław, Cyprian, Makary, Marceli, Porfirion',
							"3-11" => 'Benedykt, Drogosława, Edwin, Kandyd, Konstanty, Konstantyn, Prokop, Rozyna, Sofroniusz',
							"3-12" => 'Bernard, Blizbor, Grzegorz, Józefina, Wasyl',
							"3-13" => 'Bożena, Ernest, Ernestyn, Kasjan, Krystyna, Marek, Rodryg, Roderyk, Rodryk, Trzebisław',
							"3-14" => 'Bożeciecha, Jakub, Leon, Matylda, Mechtylda, Michał',
							"3-15" => 'Gościmir, Klemens, Krzysztof, Longinus, Ludwika, Heloiza',
							"3-16" => 'Abraham, Cyriak, Henryka, Herbert, Hiacynt, Hilary, Izabela, Oktawia',
							"3-17" => 'Gertruda, Harasym, Jan, Patrycjusz, Patryk, Regina, Rena, Zbigniew, Zbyszko, Zbygniew',
							"3-18" => 'Aleksander, Anzelm, Boguchwał, Cyryl, Edward, Narcyz, Narcyza, Salwator',
							"3-19" => 'Bogdan, Józef',
							"3-20" => 'Aleksander, Aleksandra, Ambroży, Anatol, Bogusław, Cyriaka, Eufemia, Klaudia, Patrycjusz, Ruprecht, Wasyl, Wincenty',
							"3-21" => 'Benedykt, Filemon, Lubomira, Mikołaj',
							"3-22" => 'Bazylissa, Bogusław, Godzisław, Katarzyna, Kazimierz, Paweł',
							"3-23" => 'Eberhard, Feliks, Katarzyna, Kondrat, Oktawian, Pelagia, Pelagiusz, Piotr, Zbysław',
							"3-24" => 'Dzierżysława, Dziersława, Gabor, Gabriel, Marek, Sewer, Sofroniusz, Szymon',
							"3-25" => 'Dyzma, Ireneusz, Lucja, Lutomysł, Łucja, Maria, Mariola, Wieñczysław',
							"3-26" => 'Emanuel, Manuela, Feliks, Larysa, Nikifor, Teodor, Tworzymir',
							"3-27" => 'Benedykt, Ernest, Ernestyn, Jan, Lidia, Rościmir, Rupert',
							"3-28" => 'Aniela, Antoni, Jan, Krzesisław, Sykstus',
							"3-29" => 'Cyryl, Czcirad, Eustachy, Eustachiusz, Ostap, Wiktoryn',
							"3-30" => 'Amelia, Aniela, Częstobor, Jan, Kwiryna, Kwiryn',
							"3-31" => 'Amos, Balbina, Beniamin, Dobromira, Gwidon, Kirył, Korneli, Kornelia',
							"4-1" => 'Chryzant, Grażyna, Hugo, Hugon, Katarzyna, Teodora, Tolisław, Zbigniew, Zbyszko',
							"4-2" => 'Franciszek, Sądomir, Urban, Władysław, Władysława',
							"4-3" => 'Antoni, Cieszygor, Jakub, Pankracy, Ryszard',
							"4-4" => 'Ambroży, Bazyli, Benedykt, Izydor, Wacław, Wacława, Zdzimir',
							"4-5" => 'Borzywoj, Irena, Wincenty',
							"4-6" => 'Adam, Ada, Adamina, Celestyn, Celestyna, Diogenes, Ireneusz, Katarzyna, Sykstus, Świętobor, Wilhelm, Zachariasz',
							"4-7" => 'Donat, Donata, Epifaniusz, Hegezyp, Herman, Przecław, Rufin',
							"4-8" => 'Apolinary, Cezary, Cezaryna, Dionizy, Gawryła, January, Radosław, Sieciesława',
							"4-9" => 'Dobrosława, Dymitr, Maja, Marceli, Matron',
							"4-10" => 'Antoni, Apoloniusz, Daniel, Ezechiel, Grodzisław, Henryk, Makary, Małgorzata, Michał, Pompejusz',
							"4-11" => 'Filip, Herman, Jaromir, Leon, Marek',
							"4-12" => 'Andrzej, Iwan, Juliusz, Siemiodrog, Wiktor, Wiktoriusz, Zenon, Zenona',
							"4-13" => 'Hermenegilda, Hermenegild, Ida, Jan, Justyn, Małgorzata, Przemysł, Przemysław',
							"4-14" => 'Berenike, Julianna, Justyn, Maria, Myślimir, Tyburcjusz, Walerian, Waleriana',
							"4-15" => 'Anastazja, Bazyli, Leonid, Ludwina, Modest, Olimpia, Tytus, Wacław, Wacława, Wiktoryn, Wszegniew',
							"4-16" => 'Benedykt, Bernadetta, Cecyl, Cecylian, Charyzjusz, Erwin, Erwina, Julia, Ksenia, Lambert, Lamberta, Nikita, Nosisław, Patrycy, Urban',
							"4-17" => 'Anicet, Innocenty, Innocenta, Jakub, Józef, Klara, Radociech, Robert, Roberta, Rudolf, Rudolfa, Rudolfina, Stefan',
							"4-18" => 'Apoloniusz, Bogusław, Bogusława, Flawiusz, Gościsław',
							"4-19" => 'Adolf, Adolfa, Adolfina, Alf, Cieszyrad, Czesław, Czech, Czechasz, Czechoñ, Leon, Leontyna, Pafnucy, Tymon, Werner, Włodzimierz',
							"4-20" => 'Agnieszka, Amalia, Czesław, Czech, Czechasz, Czechoñ, Florencjusz, Florenty, Nawoj, Sulpicjusz, Szymon, Teodor',
							"4-21" => 'Addar, Anzelm, Bartosz, Drogomił, Feliks, Irydion, Konrad, Konrada, Selma',
							"4-22" => 'Heliodor, Kajus, Leonia, Leonid, Łukasz, Soter, Strzeżymir, Teodor',
							"4-23" => 'Adalbert, Gerard, Gerarda, Gerhard, Helena, Jerzy, Wojciech',
							"4-24" => 'Aleksander, Aleksy, Egbert, Erwin, Erwina, Fidelis, Grzegorz, Horacy, Horacjusz, Zbroimir',
							"4-25" => 'Jarosław, Marek, Wasyl',
							"4-26" => 'Artemon, Klaudiusz, Klet, Marcelin, Marcelina, Maria, Marzena, Spycimir',
							"4-27" => 'Anastazy, Andrzej, Bożebor, Kanizjusz, Martyn, Piotr, Teofil, Zyta',
							"4-28" => 'Arystarch, Maria, Paweł, Przybyczest, Waleria, Witalis',
							"4-29" => 'Angelina, Augustyn, Bogusław, Hugo, Hugon, Paulin, Piotr, Rita, Robert, Roberta, Sybilla',
							"4-30" => 'Bartłomiej, Chwalisława, Eutropiusz, Jakub, Katarzyna, Lilla, Marian',
							"5-1" => 'Aniela, Filip, Jakub, Jeremiasz, Jeremi, Józef, Lubomir',
							"5-2" => 'Anatol, Atanazy, Afanazy, Longin, Longina, Walenty, Walter, Witomir, Zygmunt',
							"5-3" => 'Aleksander, Antonina, Maria, Mariola, Świętosława',
							"5-4" => 'Florian, Grzegorz, January, Michał, Monika, Paulin, Strzeżywoj',
							"5-5" => 'Irena, Ita, Pius, Teodor, Waldemar, Zdzibor',
							"5-6" => 'Benedykta, Benita, Dytrych, Gościwit, Jan, Judyta, Jurand',
							"5-7" => 'Benedykt, Bogumir, Domicela, Flawia, Florian, Gizela, Gustawa, Ludmiła, Ludomiła, Sawa, Wincenta',
							"5-8" => 'Dezyderia, Ilza, Marek, Michał, Piotr, Stanisław',
							"5-9" => 'Beatus, Bożydar, Grzegorz, Job, Karolina, Mikołaj',
							"5-10" => 'Antonin, Częstomir, Izydor, Jan, Symeon, Wiktoryna',
							"5-11" => 'Adalbert, Benedykt, Filip, Franciszek, Ignacy, Iga, Ignacja, Żegota, Lew, Lutogniew, Mamert, Mira',
							"5-12" => 'Domicela, Domicjan, Dominik, Epifani, Flawia, Jan, Jazon, Joanna, Pankracy, Wszemił',
							"5-13" => 'Andrzej, Aron, Ciechosław, Gloria, Magdalena, Piotr, Robert, Roberta, Serwacy',
							"5-14" => 'Bonifacy, Boñcza, Dobiesław, Jeremiasz, Jeremi, Wiktor, Wiktoriusz',
							"5-15" => 'Atanazy, Afanazy, Berta, Cecyliusz, Czcibora, Dionizja, Izydor, Jan, Nadzieja, Ruprecht, Strzeżysław, Zofia',
							"5-16" => 'Andrzej, Honorat, Jan Nepomucen, Jędrzej, Szymon, Trzebomysł, Ubald, Wieñczysław, Wiktorian',
							"5-17" => 'Bruno, Herakliusz, Paschalis, Sławomir, Torpet, Weronika, Wiktor, Wiktoriusz',
							"5-18" => 'Aleksander, Aleksandra, Alicja, Edwin, Eryk, Eryka, Feliks, Irina, Liboriusz, Myślibor, Wenancjusz',
							"5-19" => 'Augustyn, Celestyn, Iwo, Mikołaj, Pękosław, Piotr, Potencjana',
							"5-20" => 'Anastazy, Asteriusz, Bazyli, Bazylid, Bazylis, Bernardyn, Bernardyna, Bronimir, Iwo, Sawa, Teodor, Wiktoria',
							"5-21" => 'Donat, Donata, Jan, Kryspin, Przecława, Pudens, Tymoteusz, Walenty, Wiktor, Wiktoriusz',
							"5-22" => 'Emil, Helena, Jan, Julia, Krzesisława, Rita, Wiesław, Wiesława, Wisława',
							"5-23" => 'Budziwoj, Dezyderiusz, Dezydery, Emilia, Iwona, Jan, Leontyna, Michał, Symeon',
							"5-24" => 'Cieszysława, Estera, Jan, Joanna, Maria, Mokij, Wincenty, Zuzanna, Zula',
							"5-25" => 'Epifan, Grzegorz, Imisława, Maria Magdalena, Urban',
							"5-26" => 'Beda, Filip, Marianna, Paulina, Więcemił, Wilhelmina',
							"5-27" => 'Beda, Izydor, Jan, Juliusz, Lucjan, Magdalena, Radowit',
							"5-28" => 'Augustyn, German, Jaromir, Priam, Wiktor, Wiktoriusz, Wilhelm, Wrócimir',
							"5-29" => 'Bogusława, Maksymilian, Maria Magdalena, Teodor, Teodozja',
							"5-30" => 'Andonik, Feliks, Ferdynand, Joanna, Sulimir',
							"5-31" => 'Aniela, Bożysława, Ernesta, Ernestyna, Feliks, Petronela, Petronia, Petroniusz, Teodor',
							"6-1" => 'Alfons, Alfonsyna, Bernard, Fortunat, Gracjana, Hortensjusz, Jakub, Konrad, Konrada, Magdalena, Nikodem, Symeon, Świętopełk',
							"6-2" => 'Efrem, Erazm, Eugeniusz, Marcelin, Maria, Marianna, Mikołaj, Nicefor, Piotr, Racisław',
							"6-3" => 'Cecyliusz, Ferdynand, Klotylda, Konstantyn, Laurencjusz, Wawrzyniec, Laurentyn, Laurentyna, Leszek, Paula, Tamara',
							"6-4" => 'Bazyliusz, Dacjan, Franciszek, Gościmił, Karol, Karp',
							"6-5" => 'Bonifacy, Boñcza, Dobrociech, Dobromir, Dobrymir, Nikanor, Waleria, Walter',
							"6-6" => 'Benignus, Dominika, Klaudiusz, Laurenty, Norbert, Norberta, Paulina, Symeon, Więcerad',
							"6-7" => 'Antoni, Ciechomir, Jarosław, Lukrecja, Paweł, Robert, Roberta, Wiesław, Wisław',
							"6-8" => 'Karp, Maksym, Medard, Seweryn, Wilhelm, Wyszesław',
							"6-9" => 'Felicjan, Pelagia, Pelagiusz',
							"6-10" => 'Bogumił, Edgar, Małgorzata, Mauryn, Nikita, Onufry',
							"6-11" => 'Anastazy, Barnaba, Feliks, Radomił, Teodozja',
							"6-12" => 'Antonina, Bazyli, Jan, Leon, Onufry, Wyszemir',
							"6-13" => 'Antoni, Chociemir, Herman, Lucjan, Maria Magdalena, Tobiasz',
							"6-14" => 'Bazylid, Bazylis, Eliza, Justyn, Justyna, Ninogniew, Walerian, Waleriana',
							"6-15" => 'Abraham, Angelina, Bernard, Jolanta, Leona, Leonida, Nikifor, Wit, Witold, Witolda, Witołd, Witosław, Wodzisław',
							"6-16" => 'Alina, Aneta, Benon, Budzimir, Jan, Justyna, Ludgarda',
							"6-17" => 'Adolf, Adolfa, Adolfina, Alf, Agnieszka, Drogomysł, Franciszek, Laura, Marcjan, Radomił, Rainer, Wolmar',
							"6-18" => 'Efrem, Elżbieta, Gerwazy, Leonia, Marek, Marina, Paula',
							"6-19" => 'Borzysław, Gerwazy, Julianna, Odo, Protazy, Sylweriusz',
							"6-20" => 'Bogna, Bogumiła, Bożena, Florentyna, Franciszek, Michał, Rafał, Rafęla, Sylwery',
							"6-21" => 'Albaniusz, Alicja, Alojzy, Alojza, Demetria, Domamir, Marta, Rudolf, Rudolfa, Rudolfina, Teodor',
							"6-22" => 'Achacjusz, Achacy, Agenor, Alban, Broniwoj, Flawiusz, Innocenty, Innocenta, Kirył, Paulina',
							"6-23" => 'Agrypina, Albin, Bazyli, Józef, Piotr, Prosper, Wanda, Zenon, Zenona',
							"6-24" => 'Danuta, Dan, Danisz, Emilia, Jan, Wilhelm',
							"6-25" => 'Albrecht, Eulogiusz, Lucja, Łucja, Tolisława, Wilhelm',
							"6-26" => 'Jan, Jeremiasz, Jeremi, Paweł, Zdziwoj',
							"6-27" => 'Maria Magdalena, Władysław, Władysława, Włodzisław',
							"6-28" => 'Amos, Ireneusz, Józef, Leon, Paweł, Raissa, Zbrosław',
							"6-29" => 'Benedykta, Benita, Dalebor, Paweł, Piotr',
							"6-30" => 'Alpinian, Ciechosława, Cyryl, Emilia, Lucyna, Marcjal',
							"7-1" => 'Aaron, Bogusław, Halina, Klarysa, Marian, Niegosława, Teobald',
							"7-2" => 'Juda, Maria, Martynian, Otto, Piotr, Urban',
							"7-3" => 'Anatol, Jacek, Korneli, Leon, Miłosław, Otto',
							"7-4" => 'Ageusz, Alfred, Aurelian, Elżbieta, Innocenty, Innocenta, Józef, Julian, Malwina, Malwin, Odo, Teodor, Wielisław',
							"7-5" => 'Antoni, Bartłomiej, Filomena, Jakub, Karolina, Michał, Przybywoj, Szarlota, Wilhelm',
							"7-6" => 'Agrypina, Chociebor, Dominik, Dominika, Goar, Gotard, Lucja, Łucja, Niegosław',
							"7-7" => 'Antoni, Benedykt, Cyryl, Estera, Kira, Metody, Piotr, Pompejusz, Sędzisława, Wilibald',
							"7-8" => 'Adrian, Adrianna, Chwalimir, Edgar, Elżbieta, Eugeniusz, Kilian, Prokop, Wirginia',
							"7-9" => 'Anatolia, Hieronim, Lucja, Ludwika, Heloiza, Lukrecja, Łucja, Mikołaj, Patrycjusz, Weronika, Wszebąd, Zenon, Zenona',
							"7-10" => 'Aleksander, Amelia, Aniela, Filip, January, Radziwoj, Rufina, Samson, Sylwan, Sylwana, Witalis',
							"7-11" => 'Benedykt, Cyprian, Kalina, Kallina, Kir, Olga, Pelagia, Pelagiusz, Pius, Placyd, Sawin, Wyszesława',
							"7-12" => 'Andrzej, Euzebiusz, Feliks, Henryk, Jan Gwalbert, Paweł, Piotr, Tolimir, Weronika',
							"7-13" => 'Ernest, Ernestyn, Eugeniusz, Irwin, Jakub, Justyna, Małgorzata, Radomiła',
							"7-14" => 'Bonawentura, Damian, Dobrogost, Franciszek, Izabela, Kosma, Marceli, Marcelin, Marcelina, Stella, Ulryk, Ulrych, Ulryka',
							"7-15" => 'Daniel, Dawid, Dawida, Egon, Henryk, Ignacy, Iga, Ignacja, Żegota, Lubomysł, Niecisław, Włodzimierz',
							"7-16" => 'Andrzej, Benedykt, Dzierżysław, Dziersław, Eustachy, Eustachiusz, Faust, Maria Magdalena, Marika, Ostap, Ruta, Stefan',
							"7-17" => 'Aleksander, Aleksy, Andrzej, Bogdan, Dzierżykraj, Januaria, Julietta, Leon, Marceli, Marcelina, Maria Magdalena',
							"7-18" => 'Arnold, Arnolf, Erwin, Erwina, Kamil, Karolina, Robert, Roberta, Szymon, Unisław, Wespazjan',
							"7-19" => 'Alfred, Arseniusz, Lutobor, Rufin, Wincenty, Wodzisław',
							"7-20" => 'Czesław, Czech, Czechasz, Czechoñ, Eliasz, Heliasz, Hieronim, Leon, Małgorzata, Paweł, Sewera',
							"7-21" => 'Andrzej, Benedykt, Daniel, Paulina, Prakseda, Prokop, Stojsław, Wiktor, Wiktoriusz',
							"7-22" => 'Albin, Bolesława, Bolisława, Laurencjusz, Wawrzyniec, Maria Magdalena, Milenia, Pankracy, Więcemiła',
							"7-23" => 'Apolinary, Bogna, Żelisław',
							"7-24" => 'Antoni, Kinga, Krystyna, Kunegunda, Olga, Wojciecha',
							"7-25" => 'Jakub, Krzysztof, Nieznamir, Sławosz, Walentyna',
							"7-26" => 'Anna, Bartolomea, Grażyna, Mirosława',
							"7-27" => 'Alfons, Alfonsyna, Aureli, Julia, Laurenty, Lilla, Marta, Natalia, Natalis, Pantaleon, Rudolf, Rudolfa, Rudolfina, Wszebor',
							"7-28" => 'Innocenty, Innocenta, Marcela, Pantaleon, Samson, Świętomir, Wiktor, Wiktoriusz',
							"7-29" => 'Beatrycze, Beatrice, Beatryks, Cierpisław, Faustyn, Konstantyn, Lucylla, Maria, Marta, Olaf, Serafina, Urban',
							"7-30" => 'Abdon, Julia, Julita, Ludmiła, Maryna, Ubysław',
							"7-31" => 'Beatus, Demokryt, Emilian, Ernesta, Ernestyna, Helena, Ignacy, Iga, Ignacja, Żegota, Justyn, Ludomir',
							"8-1" => 'Brodzisław, Justyn, Konrad, Konrada, Nadia, Piotr',
							"8-2" => 'Alfons, Alfonsyna, Borzysława, Gustaw, Ilia, Karina, Maria, Stefan',
							"8-3" => 'August, Augusta, Krzywosąd, Lidia, Nikodem, Symeon, Szczepan',
							"8-4" => 'Alfred, Arystarch, Dominik, Maria, Mironieg, Protazy',
							"8-5" => 'Cyriak, Emil, Karolin, Maria, Nonna, Oswald, Oswalda, Stanisława',
							"8-6" => 'Felicysym, Jakub, January, Sława, Stefan, Sykstus, Wincenty',
							"8-7" => 'Albert, Alberta, Albertyna, Anna, Dobiemir, Donat, Donata, Doris, Dorota, Kajetan',
							"8-8" => 'Cyprian, Cyriak, Cyryl, Emil, Emilian, Emiliusz, Niezamysł, Olech, Sylwiusz',
							"8-9" => 'Jan, Klarysa, Miłorad, Roland, Roman, Romuald',
							"8-10" => 'Asteria, Bernard, Bogdan, Borys, Filomena, Laurencjusz, Wawrzyniec, Prochor, Wierzchosław',
							"8-11" => 'Aleksander, Herman, Ligia, Lukrecja, Włodzimierz, Włodziwoj, Zuzanna, Zula',
							"8-12" => 'Bądzisław, Hilaria, Klara, Lech, Leonida, Piotr',
							"8-13" => 'Diana, Dianna, Gertruda, Helena, Hipolit, Hipolita, Jan, Kasjan, Radomiła, Wojbor',
							"8-14" => 'Alfred, Atanazja, Dobrowój, Euzebiusz, Kalikst, Kaliksta, Machabeusz',
							"8-15" => 'Maria, Napoleon, Stefan, Stella, Trzebimir',
							"8-16" => 'Alfons, Alfonsyna, Ambroży, Domarad, Domarat, Joachim, Joachima, Roch',
							"8-17" => 'Anastazja, Angelika, Anita, Bertram, Eliza, Jacek, Jaczewoj, Joanna, Julianna, Liberat, Miron, Zawisza, Żanna',
							"8-18" => 'Agapit, Bogusława, Bronisław, Bronisz, Helena, Ilona, Klara, Tworzysława',
							"8-19" => 'Bolesław, Emilia, Jan, Julian, Juliusz, Ludwik, Piotr, Sebald',
							"8-20" => 'Bernard, Jan, Sabin, Samuel, Samuela, Sieciech, Szwieciech, Świeciech, Sobiesław',
							"8-21" => 'Adolf, Adolfa, Adolfina, Alf, Bernard, Emilian, Filipina, Franciszek, Joanna, Kazimiera, Męcimir',
							"8-22" => 'Cezary, Dalegor, Fabrycjan, Fabrycy, Hipolit, Hipolita, Maria, Namysław, Oswald, Oswalda, Tymoteusz, Zygfryd',
							"8-23" => 'Apolinary, Benicjusz, Filip, Laurenty, Sulirad, Walerian, Waleriana, Zacheusz',
							"8-24" => 'Bartłomiej, Cieszymir, Jerzy, Joanna, Malina, Michalina',
							"8-25" => 'Gaudencjusz, Gaudenty, Grzegorz, Ludwik, Luiza, Michał, Sieciesław',
							"8-26" => 'Dobroniega, Joanna, Konstanty, Maksym, Maria, Wiktorian, Zefir, Zefiryn, Zefiryna',
							"8-27" => 'Angel, Angelus, Cezary, Gebhard, Józef, Kalasanty, Małgorzata, Przybymir, Rufus, Teodor',
							"8-28" => 'Adelina, Aleksander, Aleksy, Augustyn, Patrycja, Sobiesław, Stronisław',
							"8-29" => 'Flora, Jan, Racibor, Sabina',
							"8-30" => 'Adaukt, Częstowoj, Gaudencja, Miron, Rebeka, Róża, Szczęsny, Szczęsna, Tekla',
							"8-31" => 'Bohdan, Paulina, Rajmund, Rajmunda, Świętosław',
							"9-1" => 'Bronisław, Bronisz, Bronisława, Idzi',
							"9-2" => 'Absalon, Bohdan, Czesław, Czech, Czechasz, Czechoñ, Dersław, Dionizy, Eliza, Henryk, Julian, Stefan, Tobiasz, Wilhelm, Witomysł',
							"9-3" => 'Antoni, Bartłomiej, Bazylissa, Bronisław, Bronisz, Erazma, Eufemia, Eufrozyna, Izabela, Jan, Joachim, Joachima, Manswet, Mojmir, Szymon, Wincenty, Zenon, Zenona',
							"9-4" => 'Agatonik, Ida, Lilianna, Rościgniew, Rozalia, Róża',
							"9-5" => 'Dorota, Herakles, Herkules, Herkulan, Justyna, Laurencjusz, Wawrzyniec, Stronisława',
							"9-6" => 'Albin, Beata, Eugenia, Eugeniusz, Magnus, Michał, Uniewit, Zachariasz',
							"9-7" => 'Domasława, Domisława, Marek, Melchior, Regina, Rena, Ryszard',
							"9-8" => 'Adrian, Adrianna, Klementyna, Maria, Nestor, Radosław, Radosława',
							"9-9" => 'Augustyna, Aureliusz, Dionizy, Gorgoncjusz, Pimen, Piotr, Sergiusz, Sobiesąd, Ścibor, Ścibora',
							"9-10" => 'Aldona, Łukasz, Mikołaj, Mścibor, Pulcheria',
							"9-11" => 'Feliks, Jacek, Jan, Naczesław, Prot',
							"9-12" => 'Amadeusz, Amedeusz, Cyrus, Gwidon, Maria, Piotr, Radzimir, Sylwin',
							"9-13" => 'Aleksander, Aureliusz, Eugenia, Filip, Lubor, Materna, Morzysław, Szeliga',
							"9-14" => 'Bernard, Cyprian, Roksana, Siemomysł, Szymon',
							"9-15" => 'Albin, Budzigniew, Maria, Nikodem',
							"9-16" => 'Antym, Cyprian, Edyta, Edda, Eufemia, Eugenia, Franciszek, Jakobina, Kamila, Kornel, Lucja, Łucja, Sebastiana, Sędzisław, Wiktor, Wiktoriusz',
							"9-17" => 'Ariadna, Dezyderiusz, Drogosław, Franciszek, Hildegarda, Justyn, Justyna, Lambert, Lamberta, Narcyz, Teodora',
							"9-18" => 'Dobrowit, Irena, Irma, Józef, Ryszarda, Stefania, Tytus, Zachariasz',
							"9-19" => 'Alfons, Alfonsyna, January, Konstancja, Sydonia, Teodor, Więcemir',
							"9-20" => 'Dionizy, Eustachy, Eustachiusz, Fausta, Faustyna, Filipina, Irena, Oleg, Ostap, Sozant',
							"9-21" => 'Bożeciech, Bożydar, Hipolit, Hipolita, Ifigenia, Jonasz, Laurenty, Mateusz, Mira',
							"9-22" => 'Joachim, Joachima, Maurycy, Prosimir, Tomasz',
							"9-23" => 'Boguchwała, Bogusław, Libert, Minodora, Tekla',
							"9-24" => 'Gerard, Gerarda, Gerhard, Maria, Teodor, Tomir, Uniegost',
							"9-25" => 'Aureli, Aurelia, Aurelian, Franciszek, Gaspar, Herkulan, Kamil, Kleofas, Kleopatra, Ładysław, Piotr, Rufus, Świętopełk, Wincenty, Władysław, Władysława, Włodzisław',
							"9-26" => 'Cyprian, Euzebiusz, Justyna, Łękomir',
							"9-27" => 'Amadeusz, Amedeusz, Damian, Kosma, Przedbor, Urban',
							"9-28" => 'Jan, Laurencjusz, Wawrzyniec, Luba, Lubosza, Marek, Nikita, Salomon, Sylwin, Wacław, Wacława, Więcesław',
							"9-29" => 'Dadźbog, Franciszek, Michalina',
							"9-30" => 'Grzegorz, Hieronim, Honoriusz, Imisław, Leopard, Sofia, Wera, Wiera, Wiktor, Wiktoriusz, Zofia',
							"10-1" => 'Benigna, Cieszysław, Danuta, Dan, Danisz, Igor, Jan, Remigiusz',
							"10-2" => 'Dionizy, Leodegar, Stanimir, Teofil, Trofim',
							"10-3" => 'Eustachy, Eustachiusz, Ewald, Gerard, Gerarda, Gerhard, Heliodor, Józefa, Kandyd, Sierosław, Teresa',
							"10-4" => 'Edwin, Franciszek, Konrad, Konrada, Manfred, Manfreda, Rozalia',
							"10-5" => 'Apolinary, Częstogniew, Donat, Donata, Faust, Fides, Flawia, Igor, Justyn, Konstancjusz, Konstans, Placyd',
							"10-6" => 'Artur, Artus, Bronisław, Bronisz, Brunon, Emil, Fryderyka, Roman',
							"10-7" => 'Amalia, Justyna, Maria, Marek, Rościsława, Stefan, Tekla',
							"10-8" => 'Artemon, Brygida, Bryda, Demetriusz, Laurencja, Marcin, Pelagia, Pelagiusz, Symeon, Wojsława',
							"10-9" => 'Arnold, Arnolf, Atanazja, Bogdan, Dionizjusz, Dionizy, Jan, Ludwik, Przedpełk',
							"10-10" => 'Franciszek, German, Kalistrat, Lutomir, Paulin, Tomił',
							"10-11" => 'Aldona, Brunon, Burchard, Dobromiła, Emil, Emilian, Emiliusz, Germanik, Maria, Marian, Placydia',
							"10-12" => 'Cyriak, Eustachy, Eustachiusz, Grzymisław, Maksymilian, Ostap, Salwin, Serafin, Witold, Witolda, Witołd',
							"10-13" => 'Daniel, Edward, Gerald, Geraldyna, Maurycy, Mikołaj, Siemisław, Teofil',
							"10-14" => 'Alan, Bernard, Dominik, Dzierżymir, Fortunata, Kalikst, Kaliksta',
							"10-15" => 'Brunon, Gościsława, Jadwiga, Sewer, Tekla, Teresa',
							"10-16" => 'Ambroży, Aurelia, Dionizy, Florentyna, Galla, Gallina, Gaweł, Gerard, Gerarda, Gerhard, Grzegorz, Radzisław',
							"10-17" => 'Lucyna, Małgorzata, Marian, Sulisława, Wiktor, Wiktoriusz',
							"10-18" => 'Julian, Łukasz, René',
							"10-19" => 'Ferdynand, Fryda, Pelagia, Pelagiusz, Piotr, Siemowit, Skarbimir, Toma, Ziemowit',
							"10-20" => 'Budzisława, Irena, Jan Kanty, Kleopatra, Wendelin, Witalis',
							"10-21" => 'Bernard, Celina, Dobromił, Elżbieta, Hilary, Klemencja, Pelagia, Pelagiusz, Urszula, Wszebora',
							"10-22" => 'Abercjusz, Filip, Halka, Kordula, Kordelia, Przybysława, Sewer',
							"10-23" => 'Ignacy, Iga, Ignacja, Żegota, Jan, Marlena, Odilla, Roman, Seweryn, Teodor, Włościsław',
							"10-24" => 'Antoni, Boleczest, Filip, Hortensja, Marcin, Rafał, Rafęla, Salomon',
							"10-25" => 'Bonifacy, Boñcza, Chryzant, Daria, Inga, Kryspin, Maur, Sambor, Taras, Teodozjusz, Wilhelmina',
							"10-26" => 'Dymitriusz, Ewaryst, Eweryst, Lucyna, Ludmiła, Lutosław, Łucjan',
							"10-27" => 'Frumencjusz, Iwona, Sabina, Siestrzemił, Wincenty',
							"10-28" => 'Juda, Szymon, Tadeusz, Wszeciech',
							"10-29" => 'Euzebia, Franciszek, Longin, Longina, Lubogost, Narcyz, Teodor, Wioletta',
							"10-30" => 'Alfons, Alfonsyna, Angel, Angelus, Edmund, Klaudiusz, Przemysław, Sądosław, Zenobia',
							"10-31" => 'Alfons, Alfonsyna, Antoni, Antonina, August, Augusta, Godzimir, Godzisz, Lucylla, Łukasz, Saturnin, Saturnina, Urban, Wolfgang',
							"11-1" => 'Andrzej, Konradyn, Konradyna, Seweryn, Warcisław, Wiktoryna',
							"11-2" => 'Ambroży, Bohdana, Bożydar, Eudoksjusz, Małgorzata, Stojmir, Tobiasz, Wiktoryn',
							"11-3" => 'Bogumił, Cezary, Chwalisław, Hubert, Huberta, Sylwia',
							"11-4" => 'Emeryk, Karol Boromeusz, Mściwój, Olgierd, Witalis',
							"11-5" => 'Blandyn, Blandyna, Dalemir, Elżbieta, Florian, Modesta, Sławomir, Zachariasz',
							"11-6" => 'Feliks, Leonard, Trzebowit, Ziemowit',
							"11-7" => 'Achilles, Antoni, Engelbert, Florentyn, Melchior, Przemił',
							"11-8" => 'Dymitr, Gotfryd, Godfryd, Hadrian, Klaudiusz, Sewer, Sewerian, Seweryn, Sędziwoj, Wiktor, Wiktoriusz, Wiktoryn',
							"11-9" => 'Bogudar, Genowefa, Nestor, Teodor, Ursyn',
							"11-10" => 'Andrzej, Lena, Leon, Ludomir, Nelly, Nimfa, Probus, Stefan',
							"11-11" => 'Anastazja, Bartłomiej, Maciej, Marcin, Prot, Spycisław, Teodor',
							"11-12" => 'Czcibor, Cibor, Izaak, Jonasz, Jozafat, Konradyn, Konradyna, Krystyn, Marcin, Renata, Renat, Witold, Witolda, Witołd',
							"11-13" => 'Arkadiusz, Arkady, Brykcjusz, Eugeniusz, Jan, Mikołaj, Stanisław, Walentyn',
							"11-14" => 'Agata, Aga, Damian, Elżbieta, Emil, Emiliusz, Jozafat, Józef, Judyta, Kosma, Laurenty, Lewin, Serafin, Ścibor, Ścibora, Wszerad',
							"11-15" => 'Albert, Alberta, Albertyna, Artur, Artus, Idalia, Leopold, Leopoldyna, Przybygniew, Roger',
							"11-16" => 'Aureliusz, Dionizy, Edmund, Gertruda, Leon, Maria, Marek, Niedamir, Otomar, Paweł, Piotr',
							"11-17" => 'Dionizy, Floryn, Grzegorz, Hugo, Hugon, Salomea, Salome, Sulibor, Zbysław',
							"11-18" => 'Aniela, Cieszymysł, Filipina, Galezy, Klaudyna, Odo, Otto, Roman, Tomasz',
							"11-19" => 'Elżbieta, Mironiega, Paweł, Seweryn, Seweryna',
							"11-20" => 'Anatol, Edmund, Feliks, Jeron, Oktawiusz, Sędzimir',
							"11-21" => 'Albert, Alberta, Albertyna, Janusz, Konrad, Konrada, Maria, Piotr, Regina, Rena, Rufus, Twardosław, Wiesław',
							"11-22" => 'Cecylia, Marek, Maur, Wszemiła',
							"11-23" => 'Adela, Erast, Felicyta, Klemens, Klementyn, Orestes, Przedwoj',
							"11-24" => 'Dobrosław, Emilia, Emma, Flora, Franciszek, Gerard, Gerarda, Gerhard, Jan, Mina, Pęcisław, Protazy',
							"11-25" => 'Erazm, Jozafat, Katarzyna, Tęgomir',
							"11-26" => 'Delfin, Dobiemiest, Jan, Konrad, Konrada, Lechosław, Lechosława, Leonard, Sylwester',
							"11-27" => 'Damazy, Dominik, Leonard, Maksymilian, Oda, Stojgniew, Walery, Wirgiliusz',
							"11-28" => 'Gościrad, Grzegorz, Jakub, Lesław, Lesława, Rufin, Zdzisław',
							"11-29" => 'Błażej, Bolemysł, Fryderyk, Przemysł, Saturnin, Saturnina, Walter',
							"11-30" => 'Andrzej, Justyna, Konstanty, Maura, Zbysława',
							"12-1" => 'Długosz, Edmund, Eligiusz, Eliga, Iwa, Natalia, Natalis, Platon, Sobiesława',
							"12-2" => 'Adria, Aurelia, Balbina, Bibianna, Paulina, Sulisław, Wiktoryn, Zbylut',
							"12-3" => 'Franciszek, Kasjan, Ksawery, Lucjusz, Unimir',
							"12-4" => 'Barbara, Berno, Biernat, Chrystian, Hieronim, Krystian, Piotr',
							"12-5" => 'Anastazy, Gerald, Geraldyna, Kryspina, Krystyna, Pęcisława, Saba',
							"12-6" => 'Dionizja, Emilian, Jarema, Jarogniew, Mikołaj',
							"12-7" => 'Agaton, Ambroży, Marcin, Ninomysł',
							"12-8" => 'Boguwola, Klement, Maria, Światozar, Wirginiusz',
							"12-9" => 'Delfina, Joachim, Joachima, Leokadia, Loda, Waleria, Wielisława, Wiesław',
							"12-10" => 'Andrzej, Daniel, Judyta, Julia, Maria, Radzisława',
							"12-11" => 'Damazy, Daniela, Julia, Stefan, Waldemar, Wojmir',
							"12-12" => 'Adelajda, Aleksander, Dagmara, Paramon, Suliwoj',
							"12-13" => 'Lucja, Łucja, Otylia, Włodzisława',
							"12-14" => 'Alfred, Arseniusz, Izydor, Naum, Pompejusz, Sławobor, Spirydion',
							"12-15" => 'Celina, Fortunata, Ignacy, Iga, Ignacja, Żegota, Krystiana, Nina, Walerian, Waleriana, Wolimir',
							"12-16" => 'Adelajda, Ado, Albina, Alina, Ananiasz, Bean, Zdzisława',
							"12-17" => 'Florian, Jolanta, Łazarz, Olimpia, Warwara, Żyrosław',
							"12-18" => 'Bogusław, Gracjan, Gracjana, Laurencja, Wilibald, Wszemir',
							"12-19" => 'Abraham, Beniamin, Dariusz, Gabriela, Mścigniew, Nemezjusz, Tymoteusz, Urban',
							"12-20" => 'Amon, Bogumiła, Dominik, Liberat, Teofil',
							"12-21" => 'Balbin, Festus, Honorat, Tomasz, Tomisław',
							"12-22" => 'Beata, Drogomir, Flawian, Franciszka, Gryzelda, Honorata, Ksawera, Ksaweryna, Zenon, Zenona',
							"12-23" => 'Dagobert, Mina, Sławomir, Sławomira, Wiktoria',
							"12-24" => 'Adam, Ada, Adamina, Adela, Ewa, Ewelina, Ewelin, Godzisława, Grzegorz, Grzymisława, Hermina, Herminia, Irma, Irmina, Zenobiusz',
							"12-25" => 'Anastazja, Eugenia, Piotr, Spirydion',
							"12-26" => 'Dionizy, Szczepan, Wróciwoj',
							"12-27" => 'Cezary, Fabiola, Fabia, Jan, Radomysł',
							"12-28" => 'Antoni, Dobrowiest, Emma, Godzisław, Teofila',
							"12-29" => 'Domawit, Dominik, Gosław, Jonatan, Marcin, Tomasz, Trofim',
							"12-30" => 'Dawid, Dawida, Dionizy, Eugeniusz, Irmina, Katarzyna, Łazarz, Rainer, Sabin, Sewer, Uniedrog',
							"12-31" => 'Korneliusz, Mariusz, Melania, Sebastian, Sylwester, Tworzysław');

$hourdiff = $tdiff; // hours difference between server time and local time
$timeadjust = ($hourdiff * 3600);
$zaaa=365 - date('z') + date('L');
$dm=date('j',time() + $timeadjust);
$rok=date('Y');
$mc=date('n',time() + $timeadjust);
$data="$mc-$dm";

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.

		echo $before_widget . $before_title . $title . $after_title;
		print (''.$dzien[date('w',time() + $timeadjust)].", ".date('j',time() + $timeadjust)." ".$miesiac[date('n',time() + $timeadjust)]." ".$rok." roku.<br />");
		print ('<br />'. wordwrap($imieniny[$data],  30, '<br>', 1 ).'<br />');
		echo $after_widget;
	}


// Register the widget and control
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	
	register_sidebar_widget(array('Imieniny', 'widgets'), 'widget_imieniny');

	// This registers the (optional!) widget control form.

    	register_widget_control('Imieniny', 'widget_imieniny_control'); 


}

// Run our code later in case this loads prior to any required plugins.

add_action('widgets_init', 'widget_imieniny_init');

?>