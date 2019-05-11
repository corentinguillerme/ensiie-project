<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Un syst&egrave;me d'onglet en javascript</title>
    
    
</head>
<body>
  <div class="systeme_onglets">
    <div class="onglets">
      <span class="onglet_0 onglet" id="onglet_quoi" onclick="javascript:change_onglet('quoi');">Quoi</span>
      <span class="onglet_0 onglet" id="onglet_qui" onclick="javascript:change_onglet('qui');">Qui</span>
      <span class="onglet_0 onglet" id="onglet_pourquoi" onclick="javascript:change_onglet('pourquoi');">Pourquoi</span>
    </div>
    <div class="contenu_onglets">
      <div class="contenu_onglet" id="contenu_onglet_quoi">
        <h1>Quoi?</h1>
        Un simple syst&egrave;me d'onglet utilisant les technologies:<br />
          
      </div>
      <div class="contenu_onglet" id="contenu_onglet_qui">
          <h1>Qui?</h1>
          C'est un script r&eacute;alis&eacute; par Ybouane,<br />
          Webmaster du site <a href="http://www.supportduweb.com/">http://www.supportduweb.com/</a>
      </div>
      <div class="contenu_onglet" id="contenu_onglet_pourquoi">
          <h1>Pourquoi?</h1>
          Pour simplifier la navigation et la diviser en parties
      </div>
    </div>
  </div>
  <script type="text/javascript">
    //<!--
            var anc_onglet = 'quoi';
            change_onglet(anc_onglet);
    //-->
</script>
</body>
</html>


  
<script type="text/javascript">
        
                function change_onglet(name)
                {
                        document.getElementById('onglet_'+anc_onglet).className = 'onglet_0 onglet';
                        document.getElementById('onglet_'+name).className = 'onglet_1 onglet';
                        document.getElementById('contenu_onglet_'+anc_onglet).style.display = 'none';
                        document.getElementById('contenu_onglet_'+name).style.display = 'block';
                        anc_onglet = name;
                }
        
        </script>

<style type="text/css">
        .onglet
        {
                display:inline-block;
                margin-left:3px;
                margin-right:3px;
                padding:3px;
                border:1px solid black;
                cursor:pointer;
        }
        .onglet_0
        {
                background:#bbbbbb;
                border-bottom:1px solid black;
        }
        .onglet_1
        {
                background:#dddddd;
                border-bottom:0px solid black;
                padding-bottom:4px;
        }
        .contenu_onglet
        {
                background-color:#dddddd;
                border:1px solid black;
                margin-top:-1px;
                padding:5px;
                display:none;
        }
        ul
        {
                margin-top:0px;
                margin-bottom:0px;
                margin-left:-10px
        }
        h1
        {
                margin:0px;
                padding:0px;
        }
        </style>