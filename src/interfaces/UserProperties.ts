//
// Interface des propriétés pour les comptes utilisateurs sur GmodStore.
//  Source : https://docs.pivity.com/#tag/Users/operation/getUser
//
export interface UserProperties
{
	// Identifiant unique de l'utilisateur.
	id: string;

	// Pseudonyme de l'utilisateur.
	name: string;

	// SteamID de l'utilisateur.
	slug: string;
}