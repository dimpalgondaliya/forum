<?php

/* index_body.html */
class __TwigTemplate_b0908c184ffa1184bf629b656bfef8ae7245c0b0ec31d74260e70fc973cdadcf extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $location = "overall_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_header.html", "index_body.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
";
        // line 3
        // line 4
        echo "
";
        // line 5
        // line 6
        echo "
";
        // line 7
        $location = "forumlist_body.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("forumlist_body.html", "index_body.html", 7)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 8
        echo "
";
        // line 9
        // line 10
        echo "
<div class=\"forabg\">
\t<div class=\"inner\">
\t<ul class=\"topiclist\">
\t\t<li class=\"header\">
\t\t\t<dl class=\"row-item\">
\t\t\t\t<dt><div class=\"list-inner\">";
        // line 16
        echo $this->env->getExtension('phpbb\template\twig\extension')->lang("INFORMATION");
        echo "</div></dt>
\t\t\t</dl>
\t\t</li>
\t</ul>
\t<ul class=\"topiclist forums stats zebra-list\">
\t
\t\t";
        // line 22
        if (( !($context["S_USER_LOGGED_IN"] ?? null) &&  !($context["S_IS_BOT"] ?? null))) {
            // line 23
            echo "\t\t\t<li class=\"row\">
\t\t\t\t<div class=\"stat-block\">
\t\t\t\t\t<form method=\"post\" action=\"";
            // line 25
            echo ($context["S_LOGIN_ACTION"] ?? null);
            echo "\">
\t\t\t\t\t<h3><i class=\"icon fa-fw fa-sign-in\"></i> <a href=\"";
            // line 26
            echo ($context["U_LOGIN_LOGOUT"] ?? null);
            echo "\">";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN_LOGOUT");
            echo "</a>";
            if (($context["S_REGISTER_ENABLED"] ?? null)) {
                echo " &bull; <a href=\"";
                echo ($context["U_REGISTER"] ?? null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("REGISTER");
                echo "</a>";
            }
            echo "</h3>
\t\t\t\t\t\t<fieldset class=\"quick-login\">
\t\t\t\t\t\t\t<label for=\"username\"><span>";
            // line 28
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</span> <input type=\"text\" tabindex=\"1\" name=\"username\" id=\"username\" size=\"10\" class=\"inputbox\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("USERNAME");
            echo "\" /></label>
\t\t\t\t\t\t\t<label for=\"password\"><span>";
            // line 29
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
            echo "</span> <input type=\"password\" tabindex=\"2\" name=\"password\" id=\"password\" size=\"10\" class=\"inputbox\" title=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("PASSWORD");
            echo "\" autocomplete=\"off\" /></label>
\t\t\t\t\t\t\t";
            // line 30
            if (($context["U_SEND_PASSWORD"] ?? null)) {
                // line 31
                echo "\t\t\t\t\t\t\t\t<a href=\"";
                echo ($context["U_SEND_PASSWORD"] ?? null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("FORGOT_PASS");
                echo "</a>
\t\t\t\t\t\t\t";
            }
            // line 33
            echo "\t\t\t\t\t\t\t";
            if (($context["S_AUTOLOGIN_ENABLED"] ?? null)) {
                // line 34
                echo "\t\t\t\t\t\t\t\t<span class=\"responsive-hide\">|</span> <label for=\"autologin\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOG_ME_IN");
                echo " <input type=\"checkbox\" tabindex=\"4\" name=\"autologin\" id=\"autologin\" /></label>
\t\t\t\t\t\t\t";
            }
            // line 36
            echo "\t\t\t\t\t\t\t<input type=\"submit\" tabindex=\"5\" name=\"login\" value=\"";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LOGIN");
            echo "\" class=\"button2\" />
\t\t\t\t\t\t\t";
            // line 37
            echo ($context["S_LOGIN_REDIRECT"] ?? null);
            echo "
\t\t\t\t\t\t</fieldset>
\t\t\t\t\t</form>
\t\t\t\t</div>
\t\t\t</li>
\t\t";
        }
        // line 43
        echo "
\t\t";
        // line 44
        // line 45
        echo "
\t\t";
        // line 46
        if (($context["S_DISPLAY_ONLINE_LIST"] ?? null)) {
            // line 47
            echo "\t\t\t<li class=\"row\">
\t\t\t\t<div class=\"stat-block online-list\">
\t\t\t\t\t";
            // line 49
            if (($context["U_VIEWONLINE"] ?? null)) {
                echo "<h3><i class=\"icon fa-fw fa-users\"></i> <a href=\"";
                echo ($context["U_VIEWONLINE"] ?? null);
                echo "\">";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
                echo "</a></h3>";
            } else {
                echo "<h3><i class=\"icon fa-fw fa-users\"></i> ";
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("WHO_IS_ONLINE");
                echo "</h3>";
            }
            // line 50
            echo "\t\t\t\t\t<p>
\t\t\t\t\t\t";
            // line 51
            // line 52
            echo "\t\t\t\t\t\t";
            echo ($context["TOTAL_USERS_ONLINE"] ?? null);
            echo " (";
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("ONLINE_EXPLAIN");
            echo ")<br />";
            echo ($context["RECORD_USERS"] ?? null);
            echo "<br />
\t\t\t\t\t\t";
            // line 53
            if (($context["U_VIEWONLINE"] ?? null)) {
                // line 54
                echo "\t\t\t\t\t\t\t\t<br />";
                echo ($context["LOGGED_IN_USER_LIST"] ?? null);
                echo "
\t\t\t\t\t\t\t\t";
                // line 55
                if (($context["LEGEND"] ?? null)) {
                    echo "<br /><em>";
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("LEGEND");
                    echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                    echo " ";
                    echo ($context["LEGEND"] ?? null);
                    echo "</em>";
                }
                // line 56
                echo "\t\t\t\t\t\t";
            }
            // line 57
            echo "\t\t\t\t\t\t";
            // line 58
            echo "\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t</li>
\t\t";
        }
        // line 62
        echo "\t\t
\t\t";
        // line 63
        // line 64
        echo "
\t\t";
        // line 65
        if (($context["S_DISPLAY_BIRTHDAY_LIST"] ?? null)) {
            // line 66
            echo "\t\t\t<li class=\"row\">
\t\t\t\t<div class=\"stat-block birthday-list\">
\t\t\t\t\t<h3><i class=\"icon fa-fw fa-birthday-cake\"></i> ";
            // line 68
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("BIRTHDAYS");
            echo "</h3>
\t\t\t\t\t<p>
\t\t\t\t\t\t";
            // line 70
            // line 71
            echo "\t\t\t\t\t\t";
            if (twig_length_filter($this->env, $this->getAttribute(($context["loops"] ?? null), "birthdays", array()))) {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("CONGRATULATIONS");
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("COLON");
                echo " <strong>";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["loops"] ?? null), "birthdays", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["birthdays"]) {
                    echo $this->getAttribute($context["birthdays"], "USERNAME", array());
                    if (($this->getAttribute($context["birthdays"], "AGE", array()) !== "")) {
                        echo " (";
                        echo $this->getAttribute($context["birthdays"], "AGE", array());
                        echo ")";
                    }
                    if ( !$this->getAttribute($context["birthdays"], "S_LAST_ROW", array())) {
                        echo ", ";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['birthdays'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                echo "</strong>";
            } else {
                echo $this->env->getExtension('phpbb\template\twig\extension')->lang("NO_BIRTHDAYS");
            }
            // line 72
            echo "\t\t\t\t\t\t";
            // line 73
            echo "\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t</li>
\t\t";
        }
        // line 77
        echo "
\t\t";
        // line 78
        if (($context["NEWEST_USER"] ?? null)) {
            // line 79
            echo "\t\t\t<li class=\"row\">
\t\t\t\t<div class=\"stat-block statistics\">
\t\t\t\t\t<h3><i class=\"icon fa-fw fa-bar-chart\"></i> ";
            // line 81
            echo $this->env->getExtension('phpbb\template\twig\extension')->lang("STATISTICS");
            echo "</h3>
\t\t\t\t\t<p>
\t\t\t\t\t\t";
            // line 83
            // line 84
            echo "\t\t\t\t\t\t";
            echo ($context["TOTAL_POSTS"] ?? null);
            echo " &bull; ";
            echo ($context["TOTAL_TOPICS"] ?? null);
            echo " &bull; ";
            echo ($context["TOTAL_USERS"] ?? null);
            echo " &bull; ";
            echo ($context["NEWEST_USER"] ?? null);
            echo "
\t\t\t\t\t\t";
            // line 85
            // line 86
            echo "\t\t\t\t\t</p>
\t\t\t\t</div>
\t\t\t</li>
\t\t";
        }
        // line 90
        echo "
\t\t";
        // line 91
        // line 92
        echo "
\t</ul>
\t</div>
</div>

";
        // line 97
        $location = "overall_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("overall_footer.html", "index_body.html", 97)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "index_body.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  309 => 97,  302 => 92,  301 => 91,  298 => 90,  292 => 86,  291 => 85,  280 => 84,  279 => 83,  274 => 81,  270 => 79,  268 => 78,  265 => 77,  259 => 73,  257 => 72,  231 => 71,  230 => 70,  225 => 68,  221 => 66,  219 => 65,  216 => 64,  215 => 63,  212 => 62,  206 => 58,  204 => 57,  201 => 56,  192 => 55,  187 => 54,  185 => 53,  176 => 52,  175 => 51,  172 => 50,  160 => 49,  156 => 47,  154 => 46,  151 => 45,  150 => 44,  147 => 43,  138 => 37,  133 => 36,  127 => 34,  124 => 33,  116 => 31,  114 => 30,  107 => 29,  100 => 28,  85 => 26,  81 => 25,  77 => 23,  75 => 22,  66 => 16,  58 => 10,  57 => 9,  54 => 8,  42 => 7,  39 => 6,  38 => 5,  35 => 4,  34 => 3,  31 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "index_body.html", "");
    }
}
