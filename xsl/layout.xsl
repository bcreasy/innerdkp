<xsl:stylesheet version = "1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:php="http://php.net/xsl"
                xmlns="http://www.w3.org/1999/xhtml"
                exclude-result-prefixes="php"
>
<xsl:strip-space elements="*"/>
<xsl:output method="xml" 
            indent="yes" 
            omit-xml-declaration="yes" 
            media-type="application/xhtml+xml"
            encoding="UTF-8" 
            doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
            doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
/>

  <xsl:template match="/">
    <html>
      <xsl:apply-templates />
    </html>
  </xsl:template>

  <xsl:template match="page">
    <head>
      <title>Inner Focus DKP</title>
      <link rel="stylesheet" href="css/main.css" type="text/css" media="screen" />
      <script src="http://static.wowhead.com/widgets/power.js"></script>
      <script src="js/main.js"></script>
    </head>
    <body>
      <div id="container">
        <xsl:apply-templates />
      </div>
    </body>
  </xsl:template>

  <xsl:template match="header" name="header">
    <div id="masthead">
      <div role="banner" id="branding">
        <img width="940" height="195" alt="" src="http://www.inner-focus.org/wp-content/themes/twentyten/images/headers/innerfocus-header-2010.2.png" />
      </div>
      <div role="navigation" id="access">
        <div class="skip-link screen-reader-text"><a title="Skip to content" href="#content">Skip to content</a></div>
        <div class="menu-header">
          <ul class="menu" id="menu-inner-focus-menu">
            <li class="menu-item menu-item-type-custom menu-item-7" id="menu-item-7"><a href="/" title="Go to home page">Home</a></li>  
            <li class="menu-item menu-item-type-custom menu-item-9" id="menu-item-9"><a href="/about/">About</a>
              <ul class="sub-menu">
                <li class="menu-item menu-item-type-custom menu-item-412" id="menu-item-412"><a href="/about/">About Inner Focus</a></li>
                <li class="menu-item menu-item-type-custom menu-item-10" id="menu-item-10"><a href="/rules/">Guild Rules and Policies</a></li>
                <li class="menu-item menu-item-type-custom menu-item-13" id="menu-item-13"><a href="/members/">Members</a></li>
                <li class="menu-item menu-item-type-custom menu-item-310" id="menu-item-310"><a href="http://www.inner-focus.org/forums/showthread.php?57">Quick Reference (members only)</a></li>
              </ul>
            </li>
            <li class="menu-item menu-item-type-custom menu-item-8" id="menu-item-8"><a href="/forums/">Forums</a></li>
            <li class="menu-item menu-item-type-custom menu-item-25" id="menu-item-25"><a href="/recruitment">Recruitment</a></li>
            <li class="menu-item menu-item-type-custom menu-item-14" id="menu-item-14"><a href="/media/">Media</a></li>
            <li class="menu-item menu-item-type-custom menu-item-5" id="menu-item-5"><a href="/innerdkp/">Loot</a>
              <ul class="sub-menu">
                <li id="menu-item-352" class="menu-item menu-item-type-custom menu-item-352"><a href="/innerdkp/player_list.php">Players</a></li>
                <li id="menu-item-353" class="menu-item menu-item-type-custom menu-item-353"><a href="/innerdkp/raid_list.php">Raids</a></li>
                <li id="menu-item-354" class="menu-item menu-item-type-custom menu-item-354"><a href="/innerdkp/item_list.php">Items</a></li>
              </ul>
            </li>
            <li class="menu-item menu-item-type-custom menu-item-6" id="menu-item-6"><a href="/progression/">Progression</a></li>
            <li id="menu-item-153" class="menu-item menu-item-type-custom menu-item-153"><a href="http://www.worldoflogs.com/guilds/3808/">Logs</a></li>
          </ul>
        </div>
      </div><!-- #access -->
    </div>

    <h1>Inner Focus DKP</h1>
    <!--<xsl:call-template name="menu" />-->
  </xsl:template>

  <xsl:template match="paragraph|p" name="paragraph">
    <p><xsl:apply-templates /></p>
  </xsl:template>

  <xsl:template match="a">
    <a>
      <xsl:if test="@title"><xsl:attribute name="title"><xsl:value-of select="@title" /></xsl:attribute></xsl:if>
      <xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class" /></xsl:attribute></xsl:if>
      <xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id" /></xsl:attribute></xsl:if>
      <xsl:if test="@rel"><xsl:attribute name="rel"><xsl:value-of select="@rel" /></xsl:attribute></xsl:if>
      <xsl:if test="@href"><xsl:attribute name="href"><xsl:value-of select="@href" /></xsl:attribute></xsl:if>
      <xsl:if test="@onclick"><xsl:attribute name="onclick"><xsl:value-of select="@onclick" /></xsl:attribute></xsl:if>
      <xsl:if test="@target"><xsl:attribute name="target"><xsl:value-of select="@target" /></xsl:attribute></xsl:if>
      <xsl:apply-templates />
    </a>
  </xsl:template>

  <xsl:template match="h1">
    <h1>
      <xsl:apply-templates />
    </h1>
  </xsl:template>
  <xsl:template match="h2">
    <h2>
      <xsl:apply-templates />
    </h2>
  </xsl:template>
  <xsl:template match="h3">
    <h3>
      <xsl:apply-templates />
    </h3>
  </xsl:template>
  <xsl:template match="h4">
    <h4>
      <xsl:apply-templates />
    </h4>
  </xsl:template>

  <xsl:template match="em">
    <em>
      <xsl:apply-templates />
    </em>
  </xsl:template>

  <xsl:template match="strong">
    <strong>
      <xsl:apply-templates />
    </strong>
  </xsl:template>

  <xsl:template match="br">
    <br />
  </xsl:template>

  <xsl:template match="div">
    <div>
      <xsl:if test="@class"><xsl:attribute name="class"><xsl:value-of select="@class" /></xsl:attribute></xsl:if>
      <xsl:if test="@id"><xsl:attribute name="id"><xsl:value-of select="@id" /></xsl:attribute></xsl:if>
      <xsl:apply-templates />
    </div>
  </xsl:template>

  <xsl:template match="value">
    <xsl:variable name="name">
      <xsl:value-of select="@name" />
    </xsl:variable>
    <xsl:value-of select="php:functionString('retrieve_value', $name)" />
  </xsl:template>

  <xsl:template match="menu" name="menu">
    <div id="menu">
      <ul>
        <li><a href="player_list.php">Players</a></li>
        <li><a href="raid_list.php">Raids</a></li>
        <li><a href="item_list.php">Items</a></li>
      </ul>

      <ul id="external">
        <li><a href="/">Home</a></li>
        <li><a href="/forums/">Forums</a></li>
      </ul>
    </div>
  </xsl:template>

  <xsl:template match="content" name="content">
    <div id="content">
      <xsl:apply-templates />
    </div>
    <div class="clear"></div>
  </xsl:template>

  <xsl:template match="showTierFilter">
    <div id="filter">
      Show Tier:
      <select name="tier" id="tier" onChange="changeTier(this);">
        <xsl:for-each select="php:functionString('retrieve_tier_levels')/root/level">
          <option>
            <xsl:attribute name="value">
              <xsl:value-of select="." />
            </xsl:attribute>
            <xsl:if test="@selected = 'true'">
              <xsl:attribute name="selected">SELECTED</xsl:attribute>
            </xsl:if>
            Tier <xsl:value-of select="." />
          </option>
        </xsl:for-each>
      </select>
    </div>
  </xsl:template>

  <xsl:template match="displayPlayerTables">
<!--    <xsl:value-of select="php:functionString('retrieve_player_tables')" /> -->
    <h2>Players</h2>
    <xsl:for-each select="php:functionString('retrieve_player_tables')/playerResults/resultTable">
      <div class="class_list">
        <h3>
          <xsl:value-of select="@label" />
        </h3>
        <table>
          <xsl:apply-templates />
        </table>
      </div>
      <xsl:if test="position() = 5">
        <div class="clear" />
      </xsl:if>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="displayPlayer">
    <xsl:for-each select="php:functionString('retrieve_player')/playerView/resultTable">
      <div class="table_container">
        <h3>
          <xsl:value-of select="@label" />
        </h3>
        <table>
          <xsl:apply-templates />
        </table>
      </div>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="displayItemList">
    <h2>Items</h2>
    <xsl:for-each select="php:functionString('retrieve_item_tables')/itemResults/resultTable">
      <div class="table_container">
        <table>
          <xsl:apply-templates />
        </table>
      </div>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="displayItem">
    <xsl:for-each select="php:functionString('retrieve_item')/itemView/resultTable">
      <div class="table_container">
        <h3>
          <xsl:value-of select="@label" />
        </h3>
        <table>
          <xsl:apply-templates />
        </table>
      </div>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="displayRaidList">
    <h2>Raids</h2>
    <xsl:for-each select="php:functionString('retrieve_raid_tables')/raidResults/resultTable">
      <div class="table_container">
        <table>
          <xsl:apply-templates />
        </table>
      </div>
    </xsl:for-each>

  </xsl:template>

  <xsl:template match="displayRaid">
    <xsl:for-each select="php:functionString('retrieve_raid')/raidResults/resultTable">
      <div class="table_container">
        <table>
          <xsl:apply-templates />
        </table>
      </div>
    </xsl:for-each>
 </xsl:template>

  <xsl:template match="resultTable/head">
    <thead>
      <xsl:apply-templates />
    </thead>
  </xsl:template>

  <xsl:template match="resultTable/head/pagination">
    <tr class="pagination">
      <th colspan="4">
        <xsl:apply-templates />
      </th>
    </tr>
  </xsl:template>

  <xsl:template match="resultTable/head/columns">
    <tr>
    <xsl:for-each select="column">
      <th>
        <xsl:apply-templates />
      </th>
    </xsl:for-each>
    </tr>
  </xsl:template>

  <xsl:template match="resultTable/body">
    <tbody>
      <xsl:apply-templates />
    </tbody>
  </xsl:template>

  <xsl:template match="resultTable/body/rows">
    <xsl:for-each select="row">
      <tr>
        <xsl:if test="@clickable = 'true'">
          <xsl:attribute name="onclick">document.location = '<xsl:value-of select="@href" />'</xsl:attribute>
          <xsl:attribute name="style">cursor: pointer;</xsl:attribute>
          <xsl:attribute name="onmouseover">this.style.backgroundColor = '#CCCCCC';</xsl:attribute>
          <xsl:attribute name="onmouseout">this.style.backgroundColor = '';</xsl:attribute>
        </xsl:if>
        <xsl:choose>
          <xsl:when test="position() mod 2 = 0">
            <xsl:attribute name="class">even</xsl:attribute>
          </xsl:when>
          <xsl:otherwise>
            <xsl:attribute name="class">odd</xsl:attribute>
          </xsl:otherwise>
        </xsl:choose>
        <xsl:apply-templates />
      </tr>
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="td">
    <td>
      <xsl:apply-templates />
    </td>
  </xsl:template>

  <xsl:template match="footer">
    <div id="footer">
    </div>
  </xsl:template>

  <xsl:template match="displayWowheadLink">
    <xsl:for-each select="php:functionString('retrieve_wowhead_link')/wLinks/wLink">
      <xsl:apply-templates />
    </xsl:for-each>
  </xsl:template>

  <xsl:template match="playerlink">
    <a>
      <xsl:attribute name="href">http://us.battle.net/wow/en/character/elune/<xsl:apply-templates />/advanced</xsl:attribute>
      <xsl:apply-templates />
    </a>
  </xsl:template>
</xsl:stylesheet>
