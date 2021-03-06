How to translate verses
-----------------------

Note that the episode being translated must be defined in "verses.csv":

- the episode number must be present in the "episode-number" cell in the first line of this episode
- the letter "x" must be present in the "is-last-verse" cell in the last line of this episode
- the "url" cell of this episode MUST be blank

Note that the episode being translated is

- published in the blog message: https://roman-de-renart.blogspot.fr/2009/02/episode-en-cours-de-traduction.html
- saved in the file in the "messages" directory: 999-episode-en-cours-de-traduction.html


1. Open "verses.csv" in the "data" directory with OpenOffice
------------------------------------------------------------

Set the options:

- charset = UTF-8
- cell separator = tab (tabulation)
- text delimiter = " (double-quote)


2. Verify and fix the spelling of the next 10 or so verses to translate
-----------------------------------------------------------------------

- compare with both the original text in the paper book AND "the Corrigenda du Tome 1" in Tome 2, page 547
- fix the mistakes accordingly
- enter the characters "..." (triple dot) in the next verse that has not been checked yet
- save and close the file, do not change the CSV format settings and extension


3. Add and translate the words of those verses to "word.csv" automatically
--------------------------------------------------------------------------

In the "application" directory, run the command line: translate -a

Note: the option "-a" is equivalent to the options "-w -t".


4. Open "words.csv" in the "data" directory with OpenOffice
-----------------------------------------------------------

Set the options:

- charset = UTF-8
- cell separator = tab (tabulation)
- text delimiter = " (double-quote)
- select/highlight the column "original-word", set the column type to "Text"

Note: this will prevent the cells containing only the punctuation character "," (comma)
to be interpreted as a (French) empty number, and converted to "0".


5. Verify and fix the translation of the added words
----------------------------------------------------

- find the added words at the end of the file by their verse numbers
- check the proposed translation in the "translated-word" column if any
- fix the translation as needed
- remove the "?" (question mark) in the "not-confirmed" column
- save and close the file, do not change the CSV format settings and extension

Combined words:

- combined words should be handled as follows in order to be translated together, for example "par + mi" = "à travers"
par/par_mi  à travers
mi/par_mi   _EMPTY_
- "par" and "mi" are the original words, "par_mi" represents the combined words
- the first word, "par/par_mi" in this example, only carries the translation, "à travers" in this example
- the following words, "mi/par_mi" in this example, are flagged with the keyword "_EMPTY_".

Tips:

- leverage other translations from the "translations" column
- use the "www.micmap.org/dicfro" dictionaries: Van Daele, Romant de Renart (FHS), Moyen français (DMF), etc...


6. Update the translation of the words (optional)
-------------------------------------------------

- in the "application" directory, run the command line: translate -t
- this will the automatically update the translation of words not translated yet

Notes:

- the automatic translation process has only been implemented since 2012/8 (verse 7457)
- the translation of words has been done/initialized for verses 1-608
- the translation of words has not been verified yet for verses 609-7456


7. Update the translation of verses
-----------------------------------

In the "application" directory, run the command line: translate -u

Note: this will add the translation of the verses (those selected as explained in paragraph 2.) to "verses.csv".


8. Open "verses.csv" in the "data" directory with OpenOffice
------------------------------------------------------------

As explained in paragraph 1.


9. Fix the translation of the verses
------------------------------------

- improve the automatic translation of verses
- remove the "..." (triple dot) in the next verse that has not been translated yet
- save and close the file, do not change the CSV format settings and extension

Recommendation: fix the translation of words in "words.csv" to reflect possible changes when improving the translation.


10. Update the blog message of the episode being translated
-----------------------------------------------------------

- run the command line: update -u <login> -p <password>


11. Add new keywords "keywords.php" in the "data" directory
-----------------------------------------------------------

- Add new character names or animals etc. as keywords

Keywords are used as message labels. Labels are displayed in the blog label widget.