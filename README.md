Attendance Monitoring System
====================
This project gathers all implementations and documentations that were the result of my Engineer's Thesis project. Should you be interested in a more reader-friendly version of my work, take a look at the [official document](https://github.com/zdanowiczkonrad/AttendanceMonitoring/raw/master/docs/engineers_thesis.pdf) (Polish language only).


Intro
----
The project solves the goal of controlling a university students' attendance on courses based on her student id, being a Mifare SmartCard given a unique ID, that can be unambiguosly mapped to the student's id. 

![Project idea](https://raw.githubusercontent.com/zdanowiczkonrad/AttendanceMonitoring/master/docs/images/schemat%20glowny%20ulepszony.png)

Main functional requirements assume both contactless and contactful interaction with the students' id and an unlimited realtime control and visibility on the present course rooms and students attendances state by the authorized personnas.

The code is separated to few modules, including:
* course room client application, handling interactions with students' and lecturers' ids
* server application, responsible for updating current state of students' attendances
* administration web appplication - where all attendances, rooms, lecturers, courses and students data can be explored and modified

An end-to-end interaction of the student with the system using the student's id relies on APDU, TCP and HTTP protocols.


Desktop client
---
![Client](https://raw.githubusercontent.com/zdanowiczkonrad/AttendanceMonitoring/master/docs/images/klient%20akcja%20obecnosc.png)


Administration webapp
---
![Webapp](https://raw.githubusercontent.com/zdanowiczkonrad/AttendanceMonitoring/master/docs/images/adm%20home.png)
