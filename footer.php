<!-- Load JS scripts -->

</body>
<footer>
	<!-- footer -->
<footer class="footer">


   <div class="footer-container">


       <!-- GAUCHE : LOGO -->
       <div class="footer-left">
           <img src="log.png" alt="Logo SWAG">
       </div>


       <!-- CENTRE : LIENS -->
       <div class="footer-center">
           <a href="#top">Retour en haut</a>
           <a href="">Mentions légales</a>
           <a href="">Contact</a>
           <a href="">Politique de confidentialité</a>
           <a href="">CGU</a>
       </div>


       <!-- DROITE : RÉSEAUX + COPYRIGHT -->
       <div class="footer-right">
           <div class="footer-socials">
               <a href="">Instagram</a>
               <span>|</span>
               <a href="">TikTok</a>
               <span>|</span>
               <a href="">YouTube</a>
           </div>


           <div class="footer-copy">
               © <?= date('Y') ?> — SWAG Blog<br>
               Projet étudiant MMI
               - Tous droits réservés.
           </div>
       </div>


   </div>


</footer>


<style>
/* === FOOTER GLOBAL === */
.footer {
   background-color: #323030ff;
   color: #fff;
   padding: 60px 80px;
}


/* === CONTAINER FLEX === */
.footer-container {
   max-width: 1200px;
   margin: 0 auto;
   display: flex;
   justify-content: space-between;
   align-items: center;
}


/* === LOGO === */
.footer-left img {
   max-width: 100px;
}


/* === LIENS CENTRAUX === */
.footer-center {
   display: flex;
   gap: 20px;
   filter: drop-shadow(0 0 5px #000);
}


.footer-center a {
   color: #aaa;
   text-decoration: none;
   font-size: 0.9rem;
}


.footer-center a:hover {
   color: #FACAE0;
}


/* === DROITE === */
.footer-right {
   text-align: right;
   font-size: 0.85rem;
}


/* === RÉSEAUX === */
.footer-socials {
   margin-bottom: 10px;
   gap: 15px;
   filter: drop-shadow(0 0 5px #000);
}
.footer-socials a{
   color: #aaa;
   text-decoration: none;
   font-size: 0.9rem;
}
.footer-socials a:hover {
   color: #7999D9;
}


.footer-socials span {
   margin-left: 10px;
   opacity: 0.7;
}


/* === COPYRIGHT === */
.footer-copy {
   opacity: 0.7;
   filter: drop-shadow(0 0 5px #000);
}


/* === RESPONSIVE MOBILE === */
@media (max-width: 768px) {
   .footer-container {
       flex-direction: column;
       text-align: center;
       gap: 30px;
   }


   .footer-right {
       text-align: center;
   }
}
</style>


</body>
</html>
</footer>
</html>