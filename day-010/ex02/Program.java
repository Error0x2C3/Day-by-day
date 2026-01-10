/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package labo15.ex02;

import java.util.*;

public class Program {

    public static void main(String[] args) {
        Scanner scan = new Scanner(System.in);
        Map<Date,List<Task> > agenda = new TreeMap<>();
        Date date = new Date(); // Va poser problème -> voir commentaire en bas.
        // On cherche le 1er lundi.
        while (date.dayOfWeek() != 0) {
            date.increment();
        }
        // Tant qu'on est pas un Samedi.
        for (; date.dayOfWeek() < 5; date.increment()) {
            System.out.println("Quelles tâches effectuer ce " + date
                    + " ? (<Enter> pour terminer):");

            List<Task> taskList = new ArrayList<>();
            String s = scan.nextLine();
            while (!s.isEmpty()) {
                taskList.add(new Task(s));
                s = scan.nextLine();
            }


            /*
            agenda.put(date,taskList);
            1. Quand tu fais agenda.put(date, taskList),
            tu n'enregistres pas la "valeur" de la date à cet instant précis (ex: lundi),
            mais tu enregistres une "adresse" vers l'objet date.
                Lundi : Tu mets date (qui vaut Lundi) dans la Map.

                Mardi : Tu fais date.increment().
                L'objet date change et devient Mardi.
                Comme la Map contient une référence vers cet objet,
                l'entrée du Lundi "devient" aussi Mardi.

            Fin de boucle :
                Toutes les clés de ta TreeMap pointent vers le même objet date,
                qui aura la valeur du dernier jour calculé (le Samedi).

            2. Le comportement de la Map (TreeMap)

            Comme tu utilises une Map, chaque clé doit être unique ET immuable.
            Le lundi, tu insères une clé.
            Le mardi, tu modifies l'objet date.
            Si tu fais un nouveau put(date, ...),
            la Map voit que c'est le même objet (la même adresse mémoire).
            Elle va donc écraser la valeur précédente au lieu d'en créer une nouvelle.
            À la fin, ton agenda ne contiendra probablement qu'une seule entrée,
            ou plusieurs entrées qui pointent toutes vers le même jour final.
             */

//            agenda.put(new Date(date.getDay(),date.getMonth(),date.getYear()),taskList);
//            System.out.println(date);
//            for( Task elem : agenda.get(date)){
//                System.out.println(elem.getName());
//            }
        }
        // System.out.println( agenda.keySet() );
        String a= "a0";
        Task tache2 = new Task("aa");
        for(Date elem : agenda.keySet()){
            if(!agenda.get(elem).isEmpty()) {
                for(Task tache : agenda.get(elem)){
                    System.out.print("A faire ce "+elem+" : ");
                    System.out.print(tache.getName()+" ");
                    System.out.println();
                }
            }
        }
        System.out.println(agenda.getClass()==tache2.getClass());
    }

}
