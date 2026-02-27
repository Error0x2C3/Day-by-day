package model;

/*
Ajouter au modèle un type énuméré CounterChangeType
avec les constantes VALUE_CHANGED et NAME_CHANGED.

Lorsque l'observable notifie ses observateurs,
il leur passera une constante de ce type
pour les informer de ce qu'il s'est passé.

Adaptez votre Logger pour
qu'il écrive un message différent
en fonction de cette constante.
 */
public enum CounterChangeType {
    VALUE_CHANGED,NAME_CHANGED;
}
