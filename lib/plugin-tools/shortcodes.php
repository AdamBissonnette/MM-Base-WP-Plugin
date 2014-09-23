<?php
namespace MmmPluginToolsNamespace;

function MMBingoCard($atts)
{
    extract( shortcode_atts( array(
            'id' => '',
            'title' => '',
            'count' => '1',
            'class' => 'mmm-bingo-card'
        ), $atts));

    $content = "";

    $cardTemplate = '<table id="mmbc-%s" class=%s><h4>%s</h4>%s</table>';

    $Topics = GetTopics();
    
    //print_r($Topics);
    for ($i = 0; $i < 25; $i++)
    {
        if ($i == 0)
        {
            $content .= "<tr class=\"row-1\">";
        }
        else if ($i % 5 == 0)
        {
            $content .= sprintf("</tr>\n<tr class=\"row-%s\">\n", ($i / 5 + 1));
        }
    
        $content .= _genCardSquare($Topics[$i], ($i % 5 == 0));
    
        if ($i == 11)
        {
            $content .= _genCardSquare();
            $i++;
        }
    }

    return sprintf($cardTemplate, $id, $class, $title, $content);
}

function GetTopics() {
    $Topics = '"Do You Feel Me?"
    Beatboxing or Some Other Sound Effect
    Singing
    Listing of Literary Terms
    Has a Stage Name
    Abusive Parent
    Word that Rhymes with Shun
    Calling On God
    Naming A Jazz Musician
    Mentioning Hip Hop
    Spitting Anything (Especially Fire)
    Rape
    Words: Poem, Poet, Or Poetry
    Abortion
    Victim Of Discrimination
    Third Eye
    Reference of Self Royalty
    Any Line Repeated for the Third Time
    Drugs are Bad
    Drugs are Awesome
    Praying Before Starting Piece
    Eargasm
    Self Mutilation Confession
    Forgets Poem
    Incest
    Unsafe Sex
    Stephen Harper
    Tar Sands / Big Oil
    Fracking
    Breaking Up is Hard
    Falling in Love
    Death of a Family Member
    Occupy Movement
    Fair Trade
    World War III
    World of Warcraft
    Says "Toronto", "Vancouver", or "Saskatoon"
    Feminism, Feminist references or invokes "Judith Butler"
    Patriarchy
    Says "Queer"
    Claims to be Straight
    Race / Racism
    Sex / Sexism,
    Celebrity Reference
    Movie Quote
    Gender Roles
    Over Acting
    Nerdgasm
    Star Wars Reference
    Lord of the Rings Reference
    Superhero Reference
    Poetry on Poetry
    Crygasm / Teargasm
    Poem Stops Due To Laughter
    Famous Artist\'s Tragic Death
    Something about butterflies
    Unsung Beauty
    Masturbation
    Bathroom / Personal Hygiene Habits
    Learning Something The Hard Way
    Any Mention of Angels
    Randomly Breaking out into Dance
    Asks the Audience to Dance
    Team Piece (Multiplayer Enabled)
    Obama-Romney-Bush References
    Being a Microphone Diva / Plays with the Microphone';

    if (isset($_POST["Topics"]))
    {
        $Topics = $_POST["Topics"];
    }

    $SplitArray = explode("\n", $Topics);
    
    shuffle($SplitArray);
    
    
    return array_slice($SplitArray, 0, 25);
}

function _genCardSquare($Topic = "Star", $isFirstInRow = false)
{
    $TopicTemplate = "<td class=\"%s\"><div>%s</div></td>\n";
    $SquareClass = "topic";
    
    if ($isFirstInRow)
    {
        $SquareClass .= " first";
    }

    $Square = sprintf($TopicTemplate, $SquareClass, $Topic);
        
    if ($Topic == "Star")
    {
        $Square = '<td><div class="icon-star"><img src="img/star.png" /></div></td>';
    }
    
    return $Square;
}

add_shortcode("MMBingoCard", "MMBingoCard");

?>