package labo15.ex01;

import java.util.Map;
import java.util.Scanner;
import java.util.TreeMap;

public class Program {

    public static final String TEXT
            = "How many roads must a man walk down\n"
            + "Before you call him a man?\n"
            + "Yes, ’n’ how many seas must a white dove sail\n"
            + "Before she sleeps in the sand?\n"
            + "Yes, ’n’ how many times must the cannonballs fly\n"
            + "Before they’re forever banned?\n"
            + "The answer, my friend, is blowin’ in the wind\n"
            + "The answer is blowin’ in the wind\n"
            + "\n"
            + "How many years can a mountain exist\n"
            + "Before it’s washed to the sea?\n"
            + "Yes, ’n’ how many years can some people exist\n"
            + "Before they’re allowed to be free?\n"
            + "Yes, ’n’ how many times can a man turn his head\n"
            + "Pretending he just doesn’t see?\n"
            + "The answer, my friend, is blowin’ in the wind\n"
            + "The answer is blowin’ in the wind\n"
            + "\n"
            + "How many times must a man look up\n"
            + "Before he can see the sky?\n"
            + "Yes, ’n’ how many ears must one man have\n"
            + "Before he can hear people cry?\n"
            + "Yes, ’n’ how many deaths will it take till he knows\n"
            + "That too many people have died?\n"
            + "The answer, my friend, is blowin’ in the wind\n"
            + "The answer is blowin’ in the wind";

    public static void main(String[] args) {
        Scanner scanner = new Scanner(TEXT);
        scanner.useDelimiter("[\\p{Punct}\\s’]+");
        /*
        \p{Punct}  Tous les signes de ponctuation (. , ! ? ; : - " ( ) etc.)
        \s	       Espaces, tabulations, retours à la ligne.
        ’	       Apostrophe typographique (celle de Word).
        [...]	   Un des caractères listés.
        +	       Un ou plusieurs d’affilée.
         */
        Map<String,Integer> word_repeat = new TreeMap<>();

        while (scanner.hasNext()) {
            String word = scanner.next();
            // System.out.print("\"" + word + "\" ");
            if (word_repeat.containsKey(word)) {
                word_repeat.put(word, word_repeat.get(word)+1 );
                // System.out.println("Key: "+word+", value :"+word_repeat.get(word));
            }else{
                word_repeat.put(word,1);
                // System.out.println("Key: "+word+", value :"+word_repeat.get(word));
            }
        }
        System.out.println("------");
        System.out.println(word_repeat.keySet());
        System.out.println("-----");
        Scanner input = new Scanner(System.in);
        String searchedWord;

        do {
            System.out.print("\n\nChoisissez un mot (Enter pour terminer): ");
            searchedWord = input.nextLine();

            if(word_repeat.get(searchedWord) != null){
                System.out.println(" Le mot : --"+searchedWord+"-- revient "+word_repeat.get(searchedWord)+ " fois.");
            } else if (searchedWord == "") {
                System.out.println("Au revoir.");

            } else{
                System.out.println(" Le mot : --"+searchedWord+"-- ne revient aucune fois.");
            }

        } while (!searchedWord.isEmpty());
    }


}
