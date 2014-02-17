<?php
return array (
// Menu Text
'Manage Flows'=>'Verwalten Flow',
'Create Flow'=>'Erstellen Durchfluss',
'X2Flow Automation Rules'=>'X2Flow Automation Rules',

// Flow Editor Text
'changed'=>'geändert',
'before'=>'vorher',
'after'=>'nach',
'Compare Attribute'=>'Vergleichen Attribut',
'Current User'=>'Current User',
'Current Month'=>'Aktueller Monat',
'Day of Week'=>'Tag der Woche',
'Day of Month'=>'Tag des Monats',
'Time of Day'=>'Uhrzeit',
'Current Time'=>'Zeit',
'User Logged In'=>'Benutzer angemeldet',
'On List'=>'Auf Liste',
'Add Condition'=>'Bedingung hinzufügen',
'Add Attribute'=>'Attribut hinzufügen',

// Trigger Types
'Select a trigger'=>'Wählen Sie einen Trigger',
'Action Overdue'=>'Aktion überfällig',
'Action Marked Incomplete'=>'Aktion unvollständig gekennzeichnet',
'Campaign Email Clicked'=>'Kampagne E-Mail geklickt',
'Campaign Email Opened'=>'Kampagne Email Eröffnet',
'Unsubscribed from Campaign'=>'Unsubscribed von Kampagne',
'Campaign Web Activity'=>'Kampagne Web Activity',
'Newsletter Email Clicked'=>'Newsletter E-Mail geklickt',
'Newsletter Email Opened'=>'Newsletter E-Mail geöffnet',
'Unsubscribed from Newsletter'=>'Unsubscribed vom Newsletter',
'Tag Added'=>'Tag am',
'Tag Removed'=>'Tag entfernt',
'Record Updated'=>'Fortgeschrieben',
'Record Viewed'=>'Bilanz gesehen',
'User Signed In'=>'Benutzer Anmeldung',
'User Signed Out'=>'Benutzer abgemeldet',
'Contact Web Activity'=>'Kontakt Web Activity',

// Trigger Text
'Triggers when an action becomes overdue. Cronjob must be configured to trigger reliably.'=>'Wird ausgelöst, wenn eine Aktion überfällig. Cronjob muss so konfiguriert sein, um zuverlässig auszulösen.',
'Time Overdue (s)'=>'Überfällig Zeit (s)',
'Triggers when a contact clicks a tracking link in a campaign email.'=>'Wird ausgelöst, wenn ein Kontakt klickt auf einen Tracking-Link in einer E-Mail-Kampagne.',
'Triggers when a contact opens or clicks on a tracking link in a campaign email.'=>'Wird ausgelöst, wenn ein Kontakt öffnet oder klickt auf einen Tracking-Link in einer E-Mail-Kampagne.',
'Triggers when a contact clicks the "unsubscribe" link in a campaign email.'=>'Wird ausgelöst, wenn ein Kontakt klickt auf den Link "Abmelden" in einer Kampagne E-Mail.',
'Triggered when a contact visits your webpage by clicking a link in a campaign email.'=>'Ausgelöst, wenn ein Kontakt besucht Ihre Webseite, indem Sie auf einen Link in einer E-Mail-Kampagne.',
'Triggers when a contact clicks a tracking link in a newsletter email (no contact record available).'=>'Wird ausgelöst, wenn ein Kontakt klickt auf einen Tracking-Link in einer E-Mail-Newsletter (kein Kontakt Datensatz vorhanden).',
'Triggers when a contact opens or clicks on a tracking link in a newsletter email (no contact record available).'=>'Wird ausgelöst, wenn ein Kontakt öffnet oder klickt auf einen Tracking-Link in einer E-Mail-Newsletter (kein Kontakt Datensatz vorhanden).',
'Triggers when a contact clicks the "unsubscribe" link in a newsletter email (no contact record available).'=>'Wird ausgelöst, wenn ein Kontakt klickt auf den Link "Abmelden" in einer E-Mail-Newsletter (kein Kontakt Datensatz vorhanden).',
'Triggered when a contact visits a webpage (no contact record available).'=>'Ausgelöst, wenn ein Kontakt besucht eine Webseite (kein Kontakt Datensatz vorhanden).',
'Triggers when a new record of the specified type is created.'=>'Wird ausgelöst, wenn ein neuer Datensatz des angegebenen Typs erstellt wird.',
'Triggers when a record of specified type is deleted.'=>'Wird ausgelöst, wenn ein Datensatz des vorgeschriebenen Typs wird gelöscht.',
'Triggered by adding one of these tags to a record.'=>'Ausgelöst durch das Hinzufügen einer dieser Variablen auf einen Rekordwert.',
'Triggered when some updates a record of the the specified type.'=>'Wird ausgelöst, wenn einige Updates eine Aufzeichnung des angegebenen Typs.',
'Triggered when a user signs in to X2Engine.'=>'Ausgelöst, wenn ein Benutzer sich X2Engine.',
'Triggered when a user signs out of X2Engine.'=>'Wird ausgelöst, wenn ein Benutzer von X2Engine.',
'Triggered when a contact visits a webpage'=>'Ausgelöst, wenn ein Kontakt eine Webseite besucht',
'Triggers when a new contact fills out your web lead capture form.'=>'Wird ausgelöst, wenn ein neuer Kontakt füllt Ihre Web-Lead-Erfassung Form.',

// Action Types
'Flow Actions'=>'Flow-Aktionen',
'Remote API Call'=>'Remote API Anruf',
'Post to Activity Feed'=>'Post zu Aktivitäten-Feed',
'Create Popup Notification'=>'Erstellen Sie Popup Benachrichtigung',
'Create Record'=>'Eintrag erstellen',
'Create Action for Record'=>'Aktion erstellen für Record',
'Delete Record'=>'Datensatz löschen',
'Email Record'=>'Email Rekord',
'Email Contact'=>'E-Mail kontaktieren',
'Add to List'=>'Zur Liste hinzufügen',
'Remove from List'=>'Aus Liste entfernen',
'Reassign Record'=>'Umhängen Rekord',
'Add or Remove Tags'=>'Hinzufügen oder Entfernen Schlagwörter',
'Update Record'=>'Datensatz aktualisieren',
'Wait'=>'Warten',

// Action Text
'Conditional Switch'=>'Bedingte Schalter',
'Creates a fork in the automation flow based on your conditions.'=>'Erstellt eine Gabel in der Automatisierung Flow auf Ihrem Bedingungen.',
'Call a remote API by requesting the specified URL. You can specify the request type and any variables to be passed with the request. To improve performance, he request will be put into a job queue unless you need it to execute immediately.'=>'Rufen Sie einen Remote-API durch Anforderung der angegebenen URL. Sie können die Anfrage Art und alle Variablen mit der Anfrage übergeben werden. Um die Performance zu verbessern, fordern wird er in eine Warteschlange gestellt werden, wenn Sie es sofort ausführen müssen.',
'GET'=>'GET',
'POST'=>'POST',
'PUT'=>'PUT',
'DELETE'=>'DELETE',
'Creates an activity feed event.'=>'Erstellt eine Aktivitäts-Feed Veranstaltung.',
'Owner of Record'=>'Owner of Record',
'{Owner of Record}'=>'{Owner of Record}',
'Send a template or custom email to the specified address.'=>'Senden Sie eine Vorlage oder benutzerdefinierte E-Mail an die angegebene Adresse.',
'Creates a new action associated with the record that triggered this flow.'=>'Erstellt eine neue Aktion mit dem Datensatz, der diese Strömung ausgelöst verbunden.',
'Permanently delete this record.'=>'Dauerhaft löschen Sie diesen Eintrag.',
'Send a template or custom email to this record\'s email address. Uses the assignee\'s email unless specified.'=>'Senden Sie eine Vorlage oder benutzerdefinierte E-Mail zu diesem Eintrag  &#39;s E-Mail-Adresse ein. Verwendet der Erwerber  &#39;s email sofern nicht anders angegeben.',
'Add this record to a static list.'=>'Fügen Sie diesen Datensatz in eine statische Liste.',
'Remove this record from a static list.'=>'Entfernen Sie diesen Datensatz aus einer statischen Liste.',
'Assign the record to a user or group, or automatically using lead routing.'=>'Weisen Sie den Datensatz zu einem Benutzer oder einer Gruppe, oder automatisch mit Blei-Routing.',
'Use Lead Routing'=>'Verwenden Blei Routenplaner',
'Enter a commna-separated list of tags to add to the record'=>'Geben Sie einen commna getrennte Liste von Tags, um zu dem Datensatz hinzufügen',
'Change one or more fields on an existing record.'=>'Ändern Sie ein oder mehrere Felder auf einem vorhandenen Datensatz.',
'Delay execution of the remaining steps until the specified time.'=>'Verzögerung der Ausführung der verbleibenden Schritte bis zur angegebenen Zeit.',
'hours'=>'Stunden',
'days'=>'Tage',
'months'=>'Monate',
'Current Timestamp'=>'Aktuelle Timestamp',
'Flow configuration data appears to be corrupt.'=>'Flow-Konfigurationsdaten scheint beschädigt zu sein.',
'You must configure a trigger event.'=>'Sie müssen einen Trigger-Ereignis.',
'There must be at least one action in the flow.'=>'Es muss mindestens eine Aktion in der Strömung sein.',
'Duration (s)'=>'Dauer (s)',
'Create New Flow'=>'Create New Durchfluss',
'Message'=>'Nachricht',
'For'=>'Für',
'Post Type'=>'Sende Type',
'Create Notification?'=>'Erstellen Mitteilung?',
'User Active?'=>'Benutzer Aktiv?',
'Tags (optional)'=>'Schlagworte (optional)',
'seconds (recommended for formulas only)'=>'Sekunden (empfohlen für Formeln nur)',
'All Trigger Logs'=>'Alle Trigger-Logs',
'Trigger: '=>'Trigger:',
'Execute Branch: '=>'Führen Branch:',
'Trigger Log'=>'Trigger-Log',
'Are you sure you want to delete all trigger logs?'=>'Sind Sie sicher, dass Sie alle Trigger-Logs löschen?',
'Are you sure you want to permanently delete all trigger logs?'=>'Sind Sie sicher, dass Sie alle Trigger-Protokolle endgültig löschen?',
'Triggered At'=>'Ausgelöst An',
'Log Output'=>'Protokollausgabe',
'View Log Output'=>'View Log Output',
'Flow Name'=>'Flussnamen',
'Flow Trigger Logs'=>'Flow-Trigger Logs',
'Cron Action Succeeded'=>'Cron Aktion erfolgreich',
'Cron Action Failed'=>'Cron Aktion fehlgeschlagen',
'Viewing log file {file}'=>'Anzeigen von Protokolldatei {file}',
'Show Trigger Logs'=>'Zeige Trigger-Logs',
'View Cron Log'=>'Ansicht Cron Anmelden',
'Test Cron'=>'Test-Cron',
'Toggle Node Labels'=>'Toggle Node Labels',
'Required flow item input missing'=>'Erforderliche Eingangsstrom Artikel fehlt',
'View created record: '=>'Ansicht erstellt Rekord:',
'View created action: '=>'Ansicht erstellt Aktion:',
'Flow item configuration error: No attributes added'=>'Fluss Artikel Konfigurationsfehler: Keine Attribute hinzugefügt',
'View updated record: '=>'Aktualisierten Datensatz anzeigen:',
'User '=>'Benutzer',
'Default Web Content'=>'Standard-Web-Content-',
'conditions not passed'=>'Bedingungen nicht bestanden',
'Flow configuration data appears to be '=>'Flusskonfigurationsdaten zu sein scheint',
'There must be at least one action in the '=>'Es muss mindestens eine Aktion in der sein',
'View record: '=>'Eintrag anzeigen:',
);