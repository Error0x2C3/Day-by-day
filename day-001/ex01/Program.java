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
        Map<String,Integer> map = new TreeMap<>();

        while (scanner.hasNext()) {
            String word = scanner.next();
            System.out.print("\"" + word + "\" ");
            // System.out.println(map.get(word));
            if(map.containsKey(word)){
                map.put(word, map.get(word)+1 );
            }else{
                map.put(word,1);
            }
        }
        System.out.println();
        System.out.println(map);

        Scanner input = new Scanner(System.in);
        String searchedWord;

        do {
            System.out.print("\n\nChoisissez un mot (Enter pour terminer): ");
            searchedWord = input.nextLine();
            System.out.println("Voici le nombre d'apparition du mot "+searchedWord+" :");
            System.out.println(map.get(searchedWord));
        } while (!searchedWord.isEmpty());
    }

}
