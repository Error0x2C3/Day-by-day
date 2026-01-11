package labo16.ex02;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;

public class Program {
    public static void main(String args[]) {
        File file = new File("hamlet.txt");
        try {
            // read_file(file); => Affiche tous les mots contenus dans un fichir.
            Map<String, Integer> word_repeat = contain_file(file);
            Map<String, Integer> most_word_repeat = most_word_repeat(word_repeat);
            // Pour déterminer quel est le mot plus fréquent dans le fichier hamlet.txt.
            for (Map.Entry<String, Integer> entry : most_word_repeat.entrySet()) {
                System.out.println("Le mot le plus fréquent est : "+entry.getKey());
                System.out.println("Il se répéte plus de "+entry.getValue()+" fois.");
            }
            ArrayList<String> words_once = list_word_once(word_repeat);
            System.out.println("Les mots qui n'apparaîssent qu'une fois sont : ");
            System.out.println(words_once);
        } catch (IOException e) {
            System.out.println("Le fichier n'est pas bon.");
        }
    }

    // Affiche tous les mots contenus dans un fichier.
    public static void read_file(File file) throws IOException {
        Scanner in = new Scanner(file);
        in.useDelimiter("[\\p{Punct}\\s’]+");
        while(in.hasNext()) {
            String word = in.next();
            System.out.println(word);
        }
        // return word_repeat;
    }

    // Donne un map avec tous les mots et le nbr de fois qu'ils sont présents.
    public static Map contain_file(File file) throws IOException {
        Map<String,Integer> word_repeat = new HashMap<>();
        Scanner in = new Scanner(file);
        in.useDelimiter("[\\p{Punct}\\s’]+");
        while(in.hasNext()) {
            String word = in.next();
            if(word_repeat.containsKey(word)){
                word_repeat.put(word, word_repeat.get(word)+1);
            }else{
                word_repeat.put(word,1);
            }
            System.out.println(word);
        }
        return word_repeat;
    }

    // Pour déterminer quel est le mot plus fréquent dans le fichier hamlet.txt.
    public static Map<String,Integer> most_word_repeat(Map<String,Integer> map){
        /*
        Si l'argument est juste Map map;
        map.entrySet() est vu comme un Set<Map.Entry> brut,
        et Java ne garantit pas que les clés soient des String et les valeurs des Integer.
        👉 Solution : typer la Map
         */
        Map<String,Integer> word_most = new HashMap<>();
        int most = 0;
        String word ="";
//        for(Object elem : map.keySet()){
//            int x = (Integer)map.get(elem);
//            if(most < x){
//                most = x;
//            }
//        }
        for(Map.Entry<String,Integer> entry : map.entrySet()){
            if(most < entry.getValue()){
                most = entry.getValue();
                word = entry.getKey();
            }
        }
        word_most.put(word,most);
        return word_most;
    }

    //  la liste des mots qui sont utilisés une seule fois.
    public static ArrayList<String> list_word_once(Map<String,Integer> map){
        ArrayList<String> list_word_one= new ArrayList<>();
        for(Map.Entry<String,Integer> entry : map.entrySet()){
            if(entry.getValue() == 1){
                list_word_one.add(entry.getKey());
            }
        }
        return list_word_one;
    }
}
