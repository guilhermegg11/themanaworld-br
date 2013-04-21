<?xml version="1.0"?>
<sprite>
	<imageset name="base" src="graphics/sprites/equipment/head/head-mask-aguia.png" width="40" height="40" />

	<action name="stand" imageset="base">
		<animation direction="down">
		  <frame index="0" offsetX="0" offsetY ="-29" />
		</animation>
		<animation direction="left">
		  <frame index="1" offsetX="0" offsetY ="-29"/>
		</animation>
		<animation direction="up">
		  <frame index="2" offsetX="-1" offsetY ="-29"/>
		</animation>
		<animation direction="right">
		  <frame index="3" offsetX="0" offsetY ="-29"/>
		</animation>
	</action>

	<action name="walk" imageset="base">
		<animation direction="down">
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="0" offsetX="0" offsetY ="-29" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-29" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-29" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-29" delay="75" />
		</animation>
		<animation direction="left">
		  <frame index="1" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="1" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="1" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="1" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="1" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="1" offsetX="0" offsetY ="-29" delay="75"/>
		</animation>
		<animation direction="up">
		  <frame index="2" offsetX="-1" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-30" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-30" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-30" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-30" delay="75"/>
		</animation>
		<animation direction="right">
		  <frame index="3" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-29" delay="75"/>
		</animation>
	</action>

	<action name="attack" imageset="base">
		<animation direction="down">
		  <frame index="0" offsetX="0" offsetY ="-27" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-25" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-24" delay="75" />
		  <end />
		</animation>
		<animation direction="left">
		  <frame index="1" offsetX="4" offsetY ="-30" delay="75" />
		  <frame index="1" offsetX="4" offsetY ="-31" delay="75" />
		  <frame index="1" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="1" offsetX="-1" offsetY ="-27" delay="75" />
		  <end />
		</animation>
		<animation direction="up">
		  <frame index="2" offsetX="-2" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-27" delay="75"/>
		  <end />
		</animation>
		<animation direction="right">
		  <frame index="3" offsetX="-3" offsetY ="-30" delay="75"/>
		  <frame index="3" offsetX="-3" offsetY ="-31" delay="75"/>
		  <frame index="3" offsetX="0" offsetY ="-28" delay="75"/>
		  <frame index="3" offsetX="1" offsetY ="-27" delay="75"/>
		  <end />
		</animation>
	</action>

	<action name="attack_bow" imageset="base">
		<animation direction="down">
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-27" delay="75" />
		  <frame index="0" offsetX="0" offsetY ="-28" delay="75" />
		  <end />
		</animation>
		<animation direction="left">
		  <frame index="1" offsetX="3" offsetY ="-29" delay="75" />
		  <frame index="1" offsetX="3" offsetY ="-29" delay="75" />
		  <frame index="1" offsetX="3" offsetY ="-29" delay="75" />
		  <frame index="1" offsetX="5" offsetY ="-30" delay="75" />
		  <frame index="1" offsetX="5" offsetY ="-30" delay="75" />
		  <end />
		</animation>
		<animation direction="up">
		  <frame index="2" offsetX="-1" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-1" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-29" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-28" delay="75"/>
		  <frame index="2" offsetX="-2" offsetY ="-29" delay="75"/>
		  <end />
		</animation>
		<animation direction="right">
		  <frame index="3" offsetX="-3" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="-3" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="-3" offsetY ="-29" delay="75"/>
		  <frame index="3" offsetX="-5" offsetY ="-30" delay="75"/>
		  <frame index="3" offsetX="-5" offsetY ="-30" delay="75"/>
		  <end />
		</animation>
	</action>

	<action name="sit" imageset="base">
		<animation direction="down">
		  <frame index="0" offsetX="0" offsetY ="-17" />
		</animation>
		<animation direction="left">
		  <frame index="1" offsetX="4" offsetY ="-20"/>
		</animation>
		<animation direction="up">
		  <frame index="2" offsetX="0" offsetY ="-21"/>
		</animation>
		<animation direction="right">
		  <frame index="3" offsetX="-6" offsetY ="-20"/>
		</animation>
	</action>

	<action name="dead" imageset="base">
		<animation direction="default">
			<frame index="4" offsetX="21" offsetY="-4"/>
		</animation>
	</action>

</sprite>
