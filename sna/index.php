<?php require "includes/project_info.php" ?>
<?php require "../includes/header.php" ?>
<?php include "../includes/advert.php" ?>

<div class="section_label"><a name="team">team</a></div>
<span>
The Search, Network, and Analytics team at LinkedIn works on LinkedIn's information retrieval systems, the social graph system, data driven features, and supporting data infrastructure. This site hosts the open source projects that have been built by members of our team (and our friends).
<span>

<div class="section_label"><a name="books">books</a></div>
<span>
  <ul>
  <li>
  <i>Lucene in Depth: Advanced Search Techniques with Lucene</i> - Manning Publications (in progress)
 by Jake Mannix, John Wang, Jiong Wang, Yasuhiro Matsuda
   </li>
   <li>
  <i>R in a Nutshell</i> - O'Reilly by Joseph Adler
  </li>
  </ul>
</span>

<div class="section_label"><a name="talks">papers &amp; talks</a></div>
<span>
<ul>
  <li>Jospeh Adler &ndash; <a href="http://en.oreilly.com/datascience/public/schedule/detail/15330">O'Reilly</li>
  <li>Chris Conrad &ndash; <a href="http://days2010.scala-lang.org/node/138/159">Scala Days 2010</a></li>
	<li>Voldemort &ndash;
          <a href="http://www.vimeo.com/5187210">NoSQL SF</a>, 
          <a href="http://www.sdforum.org/index.cfm?fuseaction=Document.filterdocumentlist&topicRadio=Topic&topicOnly=32&docPublishYear=getAllYears">SDForum</a>,
          <a href="http://behemoth.strlen.net/~alex/Voldemort_NoSQL_Oakland.ppt">NoSQL Oakland</a>,
          <a href="http://qconsf.com/sf2009/file?path=/qcon-sanfran-2009/slides/JayKreps_ProjectVoldemortScalingSimpleStorageAtLinkedIn.pdf">QCon</a></li>
    </li>
    <li>Jay Kreps &ndash; <a href="http://www.slideshare.net/ydn/6-data-applicationlinkedinhadoopsummmit2010">Hadoop Summit 2010</a></li>
    <li> John Wang &ndash; 
        <a href="http://docs.google.com/present/view?id=d7qvbkn_28cgpvm96r">SDForum - LinkedIn Search</a>
    </li>
    <li>
          Igor Perisic &ndash; <a href="http://snakdd2009.socialnetworkanalysis.info/AcceptedPapers/snakdd2009_submission_7.pdf">Mapping Search Relevance to Social Networks</a> (<a href="http://snakdd2009.socialnetworkanalysis.info">KDD - Social Network Mining &amp; Analysis</a>), <a href="http://gradsymp.ist.psu.edu/2009/files/Perisic.pps">Social Networks - Search and KM</a>, <a href="http://demo.viidea.com/cikm08_perisic_usnfsw/">Using Social Networks for Social Work</a> (<a href="http://www.cikm2008.org">CIKM 2008</a>)
    </li>
    <li>
          DJ Patil &ndash; <a href="http://radar.oreilly.com/2009/04/linkedin-chief-scientist-on-analytics-and-bigdata.html">O'Reilly interview</a>, <a href="http://vodpod.com/watch/2512643-dj-patil-on-how-big-data-impacts-analytics">How Big Data Impacts Analytics</a>, <a href="http://vodpod.com/watch/2356731-dj-patil-product-analytics-at-linkedin-andreas-weigend-on-data-product-development">Data &amp; Product Development</a>, <a href="http://www.youtube.com/watch?v=se2u5RyGaNE">Social Network Visualization</a>
   </li>
   <li>
     <a href="http://snap.stanford.edu/nipsgraphs2009/papers/xiang-paper.pdf">Modeling Relationship Strength in Online Social Networks</a> 
     (<a href="http://snap.stanford.edu/nipsgraphs2009">NIPS Graphs 2009</a>)
   </li>
</ul>
   
</span>


<div class="section_label"><a name="twitter">Twitter</a></div>
<span>
 Follow us on Twitter - <a href="http://twitter.com/#/list/snaprojects/team">snaprojects/team</a>
 <script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'list',
  rpp: 30,
  interval: 6000,
  title: 'Team members',
  subject: 'SNA projects',
  width: 'auto',
  height: 300,
  theme: {
    shell: {
      background: '#fff',
      color: '#ffffff'
    },
    tweets: {
      background: '#fff',
      color: '#444444',
      links: '#6040c2'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setList('snaprojects', 'team').start();
</script>
</span>

<div class="section_label"><a name="people">people</a></div>
<span>
<?php
  function img($img_path) {
	return 'http://media.linkedin.com/mpr/mpr/shrink_80_80' . $img_path;
  }

  function echo_person($name, $title, $profile, $img_path, $blurb) {
    echo "<td class='person'>
            <a href='http://www.linkedin.com${profile}'>
              <img class='photo' src='${img_path}' width='80' height='80'>
              <div class='person_name'>${name}</div>
              <div class='person_title'>${title}</div>
            </a>
            <div>${blurb}</div>
          </td>";	
  }
?>


<table>
	<tr>
	    <?php echo_person("Joseph  Adler", "Senior Data Scientist", "/in/josephadler", "http://static03.linkedin.com/img/icon/icon_no_photo_40x40.png", ""); ?>
		<?php echo_person("Adil  Aijaz", "Senior Software Engineer", "/pub/adil-aijaz/9/91a/1a6", img("/p/1/000/041/10d/3f26d3f.jpg"), ""); ?>
		<?php echo_person("Alexis  Pribula", "Software Engineer", "/in/pribula", img("/p/1/000/01d/3e1/293a210.jpg"), ""); ?>
		<?php echo_person("Bhupesh Bansal", "Senior Software Engineer", "/in/bhupeshbansal", img("/p/3/000/000/054/1b6d75c.jpg"), ""); ?>
		<?php echo_person("Ron Bekkerman", "Senior Research Scientist", "/in/bekkerman", img("/p/2/000/032/078/22a4379.jpg"), ""); ?>
	</tr>
	<tr>
		<?php echo_person("Anmol Bhasin", "Senior Software Engineer", "/in/abhasin", img("/p/1/000/000/155/2d65dda.jpg"), ""); ?>
		<?php echo_person("Jill Chen", "Senior Site Operation Engineer", "/in/jillchen168", img("/p/2/000/01f/0d5/31c98b1.jpg"), ""); ?>	
		<?php echo_person("Heyning  Cheng", "Senior Research Scientist", "/in/heyningcheng", "http://static03.linkedin.com/img/icon/icon_no_photo_40x40.png", ""); ?>
		<?php echo_person("Cheng-Tao Chu", "Software Engineer", "/pub/cheng-tao-chu/4/80/783", "http://static03.linkedin.com/img/icon/icon_no_photo_40x40.png", ""); ?>
		<?php echo_person("Chris Conrad", "Engineering Manager/Senior Software Engineer", "/in/cconrad", img("/p/3/000/03c/318/237e99d.jpg"), ""); ?>
	</tr>
	<tr>
		<?php echo_person("Ramesh Dommeti", "Senior Software Engineer", "/in/rameshdom", "http://static03.linkedin.com/img/icon/icon_no_photo_40x40.png", ""); ?>
		<?php echo_person("Fatih Emekci", "Senior Software Engineer", "/in/fatihemekci", img("/p/3/000/007/3e3/0ebf7c8.jpg"), ""); ?>
		<?php echo_person("Alex Feinberg", "Senior Software Engineer", "/in/alexfeinberg", img("/p/3/000/028/146/3a654ae.jpg"), ""); ?>
		<?php echo_person("Xiaoyang Gu", "Software Engineer", "/pub/xiaoyang-gu/a/510/762", img("/p/3/000/023/14e/1b7d5a1.jpg"), ""); ?>
		<?php echo_person("Abhishek Gupta", "Software Engineer", "/in/abhishek85gupta", img("/p/2/000/055/0c5/293174b.jpg"), ""); ?>		
	</tr>
    <tr>
		<?php echo_person("Baq  Haidri", "Senior Software Engineer", "/in/baquera", img("/p/3/000/05e/229/05340d7.jpg"), ""); ?>
		<?php echo_person("Joshua Hartman", "Software Engineer", "/in/joshuahartman", img("/p/2/000/049/143/1febda9.jpg"), ""); ?>
    	<?php echo_person("Russell Jurney", "Senior Data Scientist", "/in/russelljurney", img("/p/2/000/05e/03a/365f589.jpg"), ""); ?>
    	<?php echo_person("Esteban Kozak", "Principal Product Manager", "/in/estebankozak", img("/p/1/000/00d/3a1/3fb949d.jpg"), ""); ?>
		<?php echo_person("Jay Kreps", "Engineering Manager/Principal Engineer", "/in/jaykreps", img("/p/3/000/059/109/0f66917.jpg"), ""); ?>
	</tr>
    <tr>
		<?php echo_person("Jake Mannix", "Principal Engineer", "/in/jakemannix", img("/p/2/000/021/019/233977b.jpg"), ""); ?>
	    <?php echo_person("Yasuhiro Matsuda", "Architect", "/in/ymatsuda", img("/p/2/000/027/149/3102a89.jpg"), ""); ?>
		<?php echo_person("David  McCutcheon", "Senior Manager Release Engineering", "/in/davidmccutcheon", img("/p/2/000/003/005/2da6652.jpg"), ""); ?>
		<?php echo_person("Mike Miller", "Principal Engineer", "/in/miketmiller", img("/p/3/000/053/289/227e8f1.jpg"), ""); ?>
		<?php echo_person("Neha Narkhede", "Senior Software Engineer", "/pub/neha-narkhede/16/35a/9a2", img("/p/1/000/04b/318/34462d7.jpg"), ""); ?>
	</tr>
	<tr>
		<?php echo_person("Richard Park", "Software Engineer", "/pub/richard-park/1/853/30b", img("/p/2/000/02d/0c1/3d29824.jpg"), ""); ?>
		<?php echo_person("DJ Patil", "Sr. Director of Product Analytics", "/in/dpatil", img("/p/1/000/00b/028/1ee7aef.jpg"), ""); ?>	
		<?php echo_person("Christian Posse", "Principal Research Scientist", "/in/christianposse", img("/p/2/000/006/09f/397db63.jpg"), ""); ?>
		<?php echo_person("Igor Perisic", "Director of Engineering", "/in/igorperisic", img("/p/1/000/003/004/126bd93.jpg"), ""); ?>
		<?php echo_person("Jun Rao", "Principal Engineer", "/pub/jun-rao/1/868/47a", img("/p/2/000/01b/284/3099192.jpg"), ""); ?>
	</tr>
	<tr>
		<?php echo_person("Chris Riccomini", "Analytics Researcher", "/in/riccomini", img("/p/1/000/013/015/0ecd6b6.jpg"), ""); ?>
		<?php echo_person("Monica Rogati", "Senior Researcher Scientist", "/in/mrogati", img("/p/1/000/025/07d/1b26553.jpg"), ""); ?>
		<?php echo_person("Janet Ryu", "Product Manager", "/in/janetryu", img("/p/1/000/03c/3d4/229807c.jpg"), ""); ?>
	    <?php echo_person("Jeffrey Schang", "Senior Software Engineer", "/in/jeffreyschang", img("/p/1/000/021/3ec/2b25c67.jpg"), ""); ?>
	    <?php echo_person("Sam Shah", "Senior Software Engineer", "/in/shahsam", img("/p/3/000/018/120/0bb01cb.jpg"), ""); ?>
    </tr>
	<tr>
		<?php echo_person("Peter Skomoroch", "Senior Research Scientist", "/in/peterskomoroch", img("/p/3/000/004/1f9/0edc5a2.jpg"), ""); ?>
		<?php echo_person("Roshan Sumbaly", "Software Engineer", "/in/rsumbaly", img("/p/2/000/021/3f0/3f5034d.jpg"), ""); ?>
		<?php echo_person("Eric Tschetter", "Senior Software Engineer", "/pub/eric-tschetter/1/b87/80", "http://static03.linkedin.com/img/icon/icon_no_photo_40x40.png", ""); ?>
		<?php echo_person("Albert Wang", "User Experience Design", "/in/albertdesign", img("/p/1/000/053/214/0bc9d98.jpg"), ""); ?>
		<?php echo_person("Jiong Wang", "Engineering Manager/Principal Engineer", "/in/jiongwang", img("/p/3/000/003/20c/23255a2.jpg"), ""); ?>
	</tr>
		<?php echo_person("John Wang", "Architect", "/in/javasoze", img("/p/3/000/022/2db/03432f1.jpg"), ""); ?>
		<?php echo_person("Rui Wang", "Senior Software Engineer", "/in/ruiwang", img("/p/2/000/013/087/23ebe1e.jpg"), ""); ?>
		<?php echo_person("Jingwei Wu", "Senior Software Engineer", "/in/wujingwei", img("/p/1/000/060/043/3a38d9e.jpg"), ""); ?>
   		<?php echo_person("Lili Wu", "Senior Software Engineer", "/pub/lili-wu/0/148/bb3", img("/p/1/000/045/2e2/186c821.jpg"), ""); ?>
   		<?php echo_person("Hao Yan", "Software Engineer", "/in/haoyan", img("/p/3/000/05c/123/381f1b1.jpg"), ""); ?>
	</tr>
	<tr>
		<?php echo_person("Ethan Zhang", "Senior Software Engineer", "/in/ethanzhang", img("/p/1/000/041/10b/10a2537.jpg"), ""); ?>
		<?php echo_person("Shannon Zhang", "Senior Software Engineer", "/in/shannonzhang", img("/p/1/000/013/260/024e687.jpg"), ""); ?>
	</tr>
</table>
</span>
<?php require "../includes/footer.php" ?>
