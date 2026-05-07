# chatBot


Planering:

Allmänna punkter:

Vad blir DB:n? Tänker vi oss en .json text fil typ där vi sparar vilken användare det är vad de skrivit, svaret och tiden det skedde. Eller gör vi en databas på riktigt som vi sparar ner det?


Alvins punkter:
1. Botens kärnfunktionalitet (Logik)Mål: Att agera som ett interaktivt uppslagsverk för kemiska formler.
  
Indata: Användaren skriver in namnet på ett kemiskt ämne (t.ex. "Metan").

Utdata: Systemet returnerar korrekt kemisk beteckning (t.ex. "CH4").

Dataformat: All kommunikation mellan klient och server ska ske via JSON för att säkerställa strukturerad dataöverföring.

2. Användargränssnitt & Dialog (Frontend)

Interaktionsmodell: En dialogruta (chattfönster) där användaren skriver sitt ämne.

Visningsläge: När ett meddelande skickas ska det renderas i en gemensam vy där man ser:Användarnamn: Ämne $\rightarrow$ Botens svar (Formel)

Gemensamt flöde: Genom att använda AJAX i kombination med Polling (att sidan frågar servern efter nya uppdateringar var tredje sekund) kan användare se varandras frågor och svar i realtid utan att ladda om sidan.

3. Navigering & UX (User Experience)

Landing Page (Framsida): En ren och tydlig välkomstsida med:Kort instruktion om tjänsten.

Ett formulär för att välja sitt användarnamn.

En tydlig "Call-to-Action"-knapp som tar användaren direkt till chattgränssnittet.

Flöde: Sidan ska kännas som en single-page application (SPA) där användaren smidigt rör sig från namngivning till interaktion utan onödiga mellansteg.



Gustavs punkter:
  Researcha sqllite:  
  Vad är sqllite, hur fungerar det, hur används det och hur kopplar jag det?  

  Skapa sqllite fil:  
    Skapa filen / tabeller  
    Medelande historik:  
      namn, vad som skickades, timestamp  

  Lägga upp projektet(folder) på subdomän samt fixa eventuella fel som uppstår.  

  Titta vilka som är online samt hur många som är online:  
    Researcha hur man gör / kollar mängden trafik.  
    Displaya hur många som är online.  

      
Aleksanders punkter:
1. researcha sqlite 

2. koppla med sqlite databasen 

3. uppdatera sidan var 3 sekund för att kunna se nya meddelanden