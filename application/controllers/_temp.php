    <h3>ISC Computer Science Practical 2013</h3>
    <div class="years">
        <ol>
            <li>
                <h4>Question 1</h4>
                <div class="hide qdesc">
            <p>
               An ISBN ( International Stanadar Book Number) is a ten digit code which uniquely identifies a book.

               The first nine digits represent the Group, Publisher and Title of the book and the last digit is used to check whehter
               ISBN is correct or not.

               Each of the first nine digits of the code can take a value between 0 and 9. Sometimes it is necessary to make the last digit equal to ten; this is done by writing the last digit
               of the code as X.
               To verify an ISBN, calculate 10 times the first digit, plus 9 times the second digit, plus 8 times the third and so on
               until we add 1 time the last digit. If the final number leaves no remainder when divided by 11, the code is a valid ISBN.

               <br />  <br />
               For example:  <br />  <br />

               1. 02011003311 = 10*0 + 9*2 + 8*0 + 7*1 + 6*1 + 5*0 + 4*3 + 3*3 + 2*1 + 1*1 = 55  <br />
               Since 55 leaves no remainder when divisible by 11, hence it is a valid ISBN.  <br />

               Design a program to accept a ten digit code from the user. For an invalid inout, display an  <br />
               appropriate message. Verify the code for its validity in the format specified below:  <br />

               Test your program with sample data and some random data.
            </p>
           <p>Example 1  </p>
<pre>
   INPUT CODE : 0201530821  <br />
   OUTPUT : SUM = 99  <br />
        LEAVES NO REMAINDER - VALID ISBN CODE  <br />  <br />
</pre>
          <p>Example 2 </p>
<pre>
   INPUT CODE : 035680324  <br />
   OUTPUT : INVALID INPUT  <br />
</pre>
          <p>Example 3 </p>
<pre>
   INPUT CODE : 0231428031  <br />
   OUTPUT : SUM = 122  <br />
            LEAVES REMAINDER - INVALID ISBN CODE  <br />
</pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
            <div class="hide qdesc">
            <p>
              Write a program to declare a square matrix A[][] of order (M X M) where 'M' is the number of rows and the number of columns such that M
              must be greater than 2 and less than 20. Allow the user to input integers into this matrix. Display appropriate error message for an invalid input. Perform the
              following tasks:<br /><br />

              (a) Display the input matrix.<br />
              (b) Create a mirror image of the inputted matrix.<br />
              (c) Display the mirror image matrix.<br /><br />

              Test your program for the following data and some random data:<br />
            </p>
            <p>Example 1 </p>
<pre>
    INPUT    :  M = 3

                4   16   12
                8    2   14
                6    1    3
    OUTPUT   :

    ORIGINAL MATRIX

                4   16   12
                8    2   14
                6    1    3

    MIRROR IMAGE MATRIX

                12   16   4
                14    2   8
                 3    1   6
</pre>

            <p>Example 2 </p>
<pre>
    INPUT    :   M = 22

    OUTPUT   :   SIZE OUT OF RANGE
</pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
            <div class="hide qdesc">
        <p>
           A palindrome is a word that may be read the same way in either direction.
           Accept a sentence in UPPER CASE which is terminated by either ".", "?", or "!".
           Each word of the sentence is separated by a single blank space.<br/>

           Perform the following taks:<br/><br/>

           (a) display the count of palindromic words in the sentence.<br/>
           (b) Display the palindromic words in the sentence.<br/><br/>

           Example of palindromic words:<br/><br/>

           MADAM, ARORA, NOON
           <br/><br/>
           Test your program with the sample data and some random data:
        </p>
        <p>Example 1</p>
<pre>
   INPUT   :   MOM AND DAD ARE COMING AT NOON
   OUTPUT  :   MOM DAD NOON
              NUMBER OF PALINDROMIC WORDS : 3
</pre>
            <br/>
           <p>Example 2</p>
<pre>
   INPUT   :  HOW ARE YOU?
   OUTPUT  :   NO PALINDROMIC WORDS
</pre>
        </div>
        </li>
        </ol>
    <?php
    $isc11=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2013'),null, true);
    ?>
    <p><a class="link-gc" class="link-gc" href="<?php echo $isc11;?>">Solutions</a></p>
    </div>
        <br />
  <h3>ISC Computer Science Practical 2012</h3>
    <div class="years">
        <ol>
        <li><h4>Question 1</h4>
            <div class="hide qdesc">
            <p>
                 A prime palindrome integer is a positive integer (without leading zeros) which is prime as well as a palindrome.
                 Given two positive integers m and n, where m &lt; n, write a program to determine how many prime-palindrome integers
                 are there in the range between m and n (both inclusive) and output them.<br />

                 The input contains two positive integers m and n where m &lt; 3000 and n &lt; 3000. Display the number of prime palindrome
                 integers in the specified range along with their values in the format specified below:<br /><br />


                Test your program with the sample data and some random data:
            </p>
            <p>Example 1</p>
<pre>
    Example 1:<br />
    INPUT: m=100<br />
    N=1000<br /><br />
    OUTPUT: The prime palindrome integers are:<br />
    101,131,151,181,191,313,351,373,383,727,757,787,797,919,929<br />

    Frequency of prime palindrome integers: 15
</pre>

            <p>Example 2</p>
<pre>
   INPUT:<br />
   M=100<br />
   N=5000<br /><br />
   OUTPUT: Out of range
</pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
            <div class="hide qdesc">
            <p>
             Write a program to accept a sentence as input. The words in the string are to be separated by a blank. Each word must be in upper case.
             The sentence is terminated by either '.','!' or '?'. Perform the following tasks:<br /><br />

            1. Obtain the length of the sentence (measured in words)<br />
            2. Arrange the sentence in alphabetical order of the words.<br /><br />

             Test your program with the sample data and some random data:<br /><br />

            <p>Example 1</p>
<pre>
     INPUT: NECESSITY IS THE MOTHER OF INVENTION.<br />
     OUTPUT:<br />
     Length: 6<br />
     Rearranged Sentence:<br />
     INVENTION IS MOTHER NECESSITY OF THE
</pre>
            <p>Example 2</p>
<pre>
     INPUT: BE GOOD TO OTHERS.<br />
     OUTPUT:<br />
     Length: 4<br />
     Rearranged Sentence: BE GOOD OTHERS TO
</pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
            <div class="hide qdesc">
            <p>
              Write a program to declare a matrix A [][] of order (MXN) where 'M' is the number of rows and 'N' is the number of<br />
              columns such that both M and N must be greater than 2 and less than 20. Allow the user to input integers into this matrix.<br />
              Perform the following tasks on the matrix:<br /><br />

              1. Display the input matrix<br />
              2. Find the maximum and minimum value in the matrix and display them along with their position.<br />
              3. Sort the elements of the matrix in ascending order using any standard sorting technique and rearrange them in the matrix.<br /><br />

              Output the rearranged matrix.
            </p>
            <p>Example 1</p>
<pre>
    INPUT:<br />
    M=3<br />
    N=4<br />

    Entered values: 8,7,9,3,-2,0,4,5,1,3,6,-4<br />


    Original matrix:<br /><br />


    8  7  9  3<br />
    -2  0  4  5<br />
    1  3  6  -4<br /><br />


    Largest Number: 9<br />
    Row: 0<br />
    Column: 2<br />
    Smallest Number: -4<br />
    Row=2<br />
    Column=3<br /><br />

    Rearranged matrix:<br /><br />

    -4  -2  0  1<br />
    3  3  4  5<br />
    6  7  8  9<br />
</pre>
        </div>
        </li>
        </ol>
    <?php
    $isc11=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2012'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc11;?>">Solutions</a></p>
    </div>
        <br />
  <h3>ISC Computer Science Practical 2011</h3>
    <div class="years">
        <ol>
        <li><h4>Question 1</h4>
                        <div class="hide qdesc">
            <p>
             Write a program to input a natural number less than 1000 and display it in words.<br />
             Test your program on the sample data and some random data.<br /><br />
             Sample input and output of the program.
            </p>
            <p>Examples</p>
<pre>
    Input: 29<br />
    Output: TWENTY NINE<br /><br />
    Input: 17001<br />
    Output: OUT OF RANGE<br /><br />
    Input: 119<br />
     Output: ONE HUNDRED AND NINETEEN<br /><br />
    Input: 500<br />
    Output: FIVE HUNDRED<br />
</pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
                        <div class="hide qdesc">
            <p>
                Encryption is a technique of coding messages to maintain their secrecy.
                A String array of size &#39n&#39 where &#39n&#39 is greater than 1 and less than 10,
                stores single sentences (each sentence ends with a full stop) in each row
                of the array.<br /><br />
                Write a program to accept the size of the array.<br /><br /> Display an appropriate
                message if the size is not satisfying the given condition.<br /><br />
                Define a string array of the inputted size and fill it with
                sentences row-wise. <br /><br />Change the sentence of the odd rows with
                an encryption of two characters ahead of the original character.
                Also change the sentence of the even rows by storing the sentence
                in reverse order.<br /><br /> Display the encrypted sentences as per the sample
                data given below.<br /><br />
                Test your program on the sample data and some random data.
            </p>
            <p>Example 1</p>
<pre>
    Input: n=4<br />
    IT IS CLOUDY.
    IT MAY RAIN.
    THE WEATHER IS FINE.
    IT IS COOL.<br />
    Output:
    KV KU ENQWFA.
    RAIN MAY IT.
    VJG YGCVJGT KU HKPG.
    COOL IS IT.
</pre>
            <p>Example 2</p>
            <pre>
                Input: n=13<br />
                Output: INVALID ENTRY
            </pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
                        <div class="hide qdesc">
            <p>
                Design a program which accepts your date of birth in dd mm yyyy format.
                Check whether the date entered is valid or not.<br />
                If it is valid, display &quot;VALID DATE&quot;, also compute and display
                the day number of the year for the date of birth. If it is invalid,
                display &quot;INVALID DATE&quot; and then terminate the program.<br /><br />
                Testing of the program
            </p>
            <p>Example 1</p>
<pre>
    Input: Enter your date of birth in dd mm yyyy format
    05
    01
    2010<br />
    Output: VALID DATE
    5
</pre>
            <p>Example 2</p>
<pre>
    Input: Enter your date of birth in dd mm yyyy format
    03
    04
    2010<br />
    Output: VALID DATE
    93
</pre>
            <p>Example 3</p>
<pre>
    Input: Enter your date of birth in dd mm yyyy format
    34
    06
    2010<br />
    Output: INVALID DATE
</pre>
        </div>
        </li>
        </ol>
    <?php
    $isc11=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2011'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc11;?>">Solutions</a></p>
    </div>
        <br />
    <h3>ISC Computer Science Practical 2010</h3>
    <div class="years">
    <ol>
    <li><h4>Question 1</h4>
                        <div class="hide qdesc">
        <p>A bank intends to design a program to display the denomination of an
        input amount, upto 5 digits. The available denomination with the bank are
        of rupees 1000,500,100,50,20,10,5,2 and 1.<br /><br />

        Design a program to accept the amount from the user and display the
        break-up in descending order of denominations. (i,e preference should
        be given to the highest denomination available) along with the total
        number of notes. [Note: only the denomination used should be displayed].
        Also print the amount in words according to the digits.
            </p>
            <p>Example 1</p>
<pre>
    INPUT: 14836<br /><br />
    OUTPUT: ONE FOUR EIGHT THREE SIX<br />
    DENOMINATION:<br />
    1000 X 14 =14000<br />
    500  X 1  =500<br />
    100  X 3  =300<br />
    50   X 1  =50<br />
    5    X 1  =5<br />
    1    X 1  =1
</pre>
            <p>Example 2</p>
<pre>
    EXAMPLE 2:<br /><br />
    INPUT: 235001<br />
    OUTPUT: INVALID AMOUNT<br />
</pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
                            <div class="hide qdesc">
        <p>Given the two positive integers p and q, where p < q. Write a program to determine
        how many kaprekar numbers are there in the range between &#39;p&#39; and &#39;q&#39;(both inclusive)
        and output them.About &#39;kaprekar&#39; number:<br /><br />
        A posotive whole number &#39;n&#39; that has &#39;d&#39; number of digits is squared and
        split into 2 pieces, a right hand piece that has &#39;d&#39; digits and a left hand piece
        that has remaining &#39;d&#39; or &#39;d-1&#39; digits. If sum of the pieces is equal to
        the number then it&#39;s a kaprekar number.
            </p>
                <p>Example 1</p>
<pre>INPUT:<br />
    p=1<br />
    Q=1000<br /><br />
    OUTPUT:<br /><br />
    THE KAPREKAR NUMBERS ARE:<br />
    1,9,45,55,99,297,999<br /><br />
    FREQUENCY OF KAPREKAR NUMBERS IS:8
</pre>
        </div>
        </li>

        <li><h4>Question 3</h4>
                            <div class="hide qdesc">
        <p>Input a paragraph containing &#39;n&#39; number of sentences where (1<=n<=4). The words are to be separated with single blank space and are in upper case. A sentence may be terminated either with a full stop (.) or question mark (?).
        Perform the followings:<br /><br />
        (i) Enter the number of sentences, if it exceeds the limit show a message.<br />
        (ii) Find the number of words in the paragraph<br />
        (iii) Display the words in ascending order with frequency.
            </p>
                <p>Example 1</p>
<pre>
    INPUT: Enter number of sentences: 1<br />
    Enter sentences:<br />
    TO BE OR NOT TO BE.<br /><br />
    OUTPUT:<br />
    Total number of words: 6<br />

    WORD        FREQUENCY
    OR              1
    NOT             1
    TO              2
    BE              2
</pre>

                <p>Example 2</p>
<pre>
    INPUT: Enter number of sentences: 3<br />
    Enter sentences:<br />
    THIS IS A STRING PROGRAM. IS THIS EASY? YES, IT IS.<br /><br />
    OUTPUT:<br />
    Total number of words: 11<br />
   WORD        FREQUENCY
    A              1
    STRING         1
    PROGRAM        1
    EASY           1
    YES            1
    IT             1
    THIS           2
    IS             3
</pre>

            </div>
        </li>
        </ol>
    <?php
    $isc10=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2010'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc10;?>">Solutions</a></p>
    </div>
        <br />
        <div class="text-center ad_big">
<!--        <script type="text/javascript">-->
<!--        google_ad_client = "ca-pub-1662490668144398";-->
<!--        /* papers */-->
<!--        google_ad_slot = "6342513298";-->
<!--        google_ad_width = 336;-->
<!--        google_ad_height = 280;-->
<!--        //-->
<!--        </script>-->
<!--            <script type="text/javascript"-->
<!--                    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">-->
<!--            </script>-->

        </div><br />
    <h3>ISC Computer Science Practical 2009</h3>
    <div class="years">
    <ol>
        <li><h4>Question 1</h4>
                            <div class="hide qdesc">
        <p>
        Design a program to accept a day number (between 1 and 366), year (in 4 digits)
        from the user to generate and display the corresponding date. Also accept &#39;N&#39;
        (1<=N<=100) from the user to compute and display the future date corresponding
        to &#39;N&#39; days after the generated date. Display error message if the value of the
        day number, year and N are not within the limit or not according to the condition specified.
        Test your program for the following data and some random data.
        </p>
        <p>Examples</p>
<pre>
    INPUT :   DAY NUMBER : 233	YEAR : 2008		DATE AFTER(N) : 17<br />
    OUTPUT: 20TH AUGUST 2008	DATE AFTER 17 DAYS : 6TH SEPTEMBER 2008<br />
    INPUT :    DAY NUMBER : 360    YEAR : 2008		DATE AFTER(N) : 45<br />
    OUTPUT:	25TH DECEMBER 2008	DATE AFTER 45 DAYS : 8TH FEBRUARY 2009<br />
</pre>
        </div>
        </li>

        <li><h4>Question 2</h4>
                            <div class="hide qdesc">
        <p>
        Write a program to declare a matrix A[ ][ ] of order (m*n) where &#39;m&#39; is the number of
        rows and   n is the number of columns such that both m and n must be greater than 2 and less
        than 20.<br />
        Allow the user to input positive integers into this matrix. Perform the following tasks on the
        matrix:<br /><br />
        (a) Sort the elements of the outer row and column elements in ascending order using any
        standard sorting technique.<br />
        (b) Calculate the sum of the outer row and column elements.<br />
        (c) Output the original matrix, rearranged matrix, and only the boundary elements of
        the rearranged array with their sum.<br /><br />
        Test your program for the following data and some random data.
        </p>

        <p>Example 1</p>
<pre>
       INPUT : M=3, N=3
                    1	7	4
                    8	2	5
                    6	3	9
    OUTPUT :
            ORIGINAL MATRIX :
            1	7	4
            8	2	5
            6	3	9
            REARRANGED MATRIX :
            1	3	4
            9	2	5
            8	7	6
            BOUNDARY ELEMENTS :
            1	3	4
            9		5
            8	7	6
    SUM OF OUTER ROW AND OUTER COLUMN = 43
</pre>
        </div>
        </li>

        <li><h4>Question 3</h4>
                            <div class="hide qdesc">
        <p>Read a single sentence which terminates with a full stop(.).
        The words are to be separated with a single blank space and are in lower case.
        Arrange the words contained in the sentence according to the length of the words
        in ascending order. If two words are of the same length then the word occurring
        first in the input sentence should come first. For both, input and output the
        sentence must begin in upper case.
        </p>
        <p>Examples</p>
<pre>
    Test your program for the following data and some random data.<br />
   INPUT 	: The lines are printed in reverse order.<br />
   OUTPUT 	: In the are lines order printed reverse.<br /><br />
   INPUT	: Print the sentence in ascending order.<br />
   OUTPUT	: In the print order sentence ascending.<br />
</pre>
        </div>
        </li>
        </ol>
    <?php
    $isc09=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2009'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc09;?>">Solutions</a></p>
    </div>

        <br />
    <h3>ISC Computer Science Practical 2008</h3>
    <div class="years">
    <ol>
        <li><h4>Question 1</h4>
                            <div class="hide qdesc">
        <p>
            A smith number is a composite number, the sum of whose digits is the sum of
            the digits of its prime factors obtained as a result of prime factorization
            (excluding 1). <br />The first few such numbers are 4, 22, 27, 58, 85, 94, 121.....
        </p>
        <p>Examples</p>
<pre>
    666 Prime factors are 2, 3, 3 and 37<br />
    Sum of the digits are (6+6+6) = 18<br />
    Sum of the digits of the factors (2+3+3+(3+7) = 18<br />
    Sample data:<br />
    Input 94 Output SMITH Number<br />
    Input 102 Output NOT SMITH Number<br />
</pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
                            <div class="hide qdesc">
        <p>
        A sentence is terminated by either &quot;.&quot;, &quot;!&quot; or &quot;?&quot; followed by a space.<br />
        Input a piece of text consisting of sentences. Assume that there will be a
        maximum of 10 sentences in a block.<br /><br />
        Write a program to:<br />
        (i) Obtain the length of the sentence (measured in words) and the frequency
        of vowels in each sentence.<br />
        (ii) Generate the output as shown below using the given data
            </p>
        <p>Examples</p>
<pre>
    INPUT HELLO! HOW ARE YOU? HOPE EVERYTHING IS FINE. BEST OF LUCK.<br />
    OUTPUT
    Sentence 	    No. of Vowels 	No. of words
    ----------------------------------------------------------
    1 		      2     	        1
    2		      5              3
    3		      8              4
    4 		      3              3


    Sentence        No. of words/vowels
    ----------------------------------------------------------
    1               VVVVVV
                    WWW
    2               VVVVVVVVVVVVVVV
                    WWWWWWWWW
    3               VVVVVVVVVVVVVVVVVVVVVVVV
                    WWWWWWWWWWWW
    ----------------------------------------------------------
    Scale used 1:3
</pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
                            <div class="hide qdesc">
        <p>Given a square matrix list [ ] [ ] of order &#39;n&#39;. The maximum value possible for &#39;n&#39;
        is 20. Input the value for &#39;n&#39; and the positive integers in the matrix and perform the
        following task:<br /><br />
        1. Display the original matrix<br />
        2. Print the row and column position of the largest element of the matrix.<br />
        3. Print the row and column position of the second largest element of the
        matrix.<br />
        4. Sort the elements of the rows in the ascending order and display the new
        matrix.
        </p>
        <p>Examples</p>
        <pre>
        INPUT:<br />
        N = 3<br />
        List [][]<br />
        5 1 3
        7 4 6
        9 8 2
        <br />
        OUTPUT<br />
        5 1 3
        7 4 6
        9 8 2
        <br />
            The largest element 9 is in row 3 and column 1<br />
        The second largest element 8 is in row 3 and column 2<br />
        Sorted list<br />
        1 3 5
        4 6 7
        2 8 9
        </pre>
        </div>
    </li>
    </ol>
    <?php
    $isc08=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2008'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc08;?>">Solutions</a></p>
    </div>
                <br />
    <h3>ISC Computer Science Practical 2007</h3>
    <div class="years">
    <ol>
        <li><h4>Question 1</h4>
                            <div class="hide qdesc">
            <p>
            Write a program to accept a date in the string format dd/mm/yyyy and accept the name of the day on 1st of January of the corresponding year. Find the day for the given date.
            </p>
                <p>Examples</p>
            <pre>
    INPUT 	Date: 5/7/2001 	Day on 1st January : MONDAY<br />
    OUTPUT	Day on 5/7/2001 : THURSDAY<br />
    Test run the program on the following inputs:<br />
    INPUT DATE         DAY ON 1ST JANUARY     OUTPUT DAY FOR INPUT DATE
     4/9/1998             THURSDAY                     FRIDAY
     31/8/1999            FRIDAY                       TUESDAY
     6/12/2000            SATURDAY                     WEDNESDAY
            </pre>
            <p>The program should include the part for validating the inputs namely the date and the day on 1st January of that year.</p>
        </div>
        </li>
        <li><h4>Question 2</h4>
                            <div class="hide qdesc">
            <p>
                The input in this problem will consist of a number of lines of English text consisting of the letters of the English alphabet,
                the punctuation marks (') apostrophe, (.) full stop, (,) comma, (;) semicolon, (:) colon and white space characters (blank, newline).
                Your task is to print the words of the text in reverse order without any punctuation marks other than blanks.<br /><br />
                For example consider the following input text:<br />
                This is a sample piece of text to illustrate this problem.<br /><br />
                If you are smart you will solve this right.<br /><br />
                The corresponding output would read as:<br /><br />
                right this solve will you smart are you If problem this illustrate to text of piece sample a is This<br />
                That is, the lines are printed in reverse order.<br />
                Note: Individual words are not reversed.
                </p>
                <p>Examples</p>
                <pre>
    INPUT FORMAT:<br />
    The first line of input contains a single integer N (<=20),
    indicating the number of lines in the input.
    This is followed by N lines of input text. Each line should
    accept a maximum of 80 characters.<br /><br />

    OUTPUT FORMAT:<br />
    Output the text containing the input lines in reverse order
    without punctuations except blanks as illustrated above.
            </pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
                            <div class="hide qdesc">
            <p>
                A unique-digit integer is a positive integer (without leading zeros) with no duplicate digits.<br />
                For example 7, 135, 214 are all unique-digit integers whereas 33, 3121, 300 are not.<br />
                Given two positive integers m and n, where m< n, write a program to determine how many unique-digit integers
                are there in the range between m and n (both inclusive) and output them.<br /><br />
                The input contains two positive integers m and n. Assume m< 30000 and n< 30000.<br /><br />
                You are to output the number of unique-digit integers in the specified range along with their values
                in the format specified below:
                </p>
                <p>Examples</p>
                <pre>
    SAMPLE DATA:<br /><br />
    INPUT: m = 100 n = 120 <br />
    OUTPUT: THE UNIQUE-DIGIT INTEGERS ARE : 102, 103, 104, 105,
                    106, 107, 108, 109, 120.<br />
    FREQUENCY OF UNIQUE-DIGIT INTEGERS IS: 9.<br />
                </pre>
        </div>
        </li>
        </ol>
    <?php
    $isc07=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2007'),null, true);
    ?>
    <p><a class="link-gc" href="<?php echo $isc07;?>">Solutions</a></p>
    </div>
        <br />
    <h3>ISC Computer Science Practical 2006</h3>
    <div class="years">
    <ol>
        <li><h4>Question 1</h4>
                            <div class="hide qdesc">
            <p>
                A positive natural number, (for e.g.27) can be represented as follows:<br />
                2+3+4+5+6+7<br />
                8+9+10<br />
                13+14<br />
                where every row represents a combination of consecutive natural numbers, which add up to 27.<br /><br />
                    Write a program which inputs a positive natural number N and prints the
                possible consecutive number combinations, which when added give N.
                Test your program for the following data and some random data.
            </p>
            <p>Examples</p>
            <pre>
            SAMPLE DATA<br />
            INPUT 	    N=15<br />
            OUTPUT	    <br />
                      1 2 3 4 5<br />
                          4 5 6<br />
                            7 8<br />
            </pre>
        </div>
        </li>
        <li><h4>Question 2</h4>
                            <div class="hide qdesc">
        <p>    Write a program that inputs the names of people into two different arrays, A and B.
        Array A has N number of names while Array B has M number of names, with no duplicates in either of them.
        Merge array A and B into a single array C, such that the resulting array is sorted alphabetically.<br /><br />
               Display all the three arrays, A, B and C, sorted alphabetically.<br />
               Test your program for the given data and some random data.
            </p>
            <p>Examples</p>
            <pre>
    INPUT  <br />
        Enter number of names in Array A, N=2 <br />
        Enter number of names in Array B, M=3<br />
        First array:(A) <br />
                Suman <br />
                Anil<br /><br />
        Second array:(B)<br />
                Usha<br />
                Sachin <br />
                John<br /><br />
    OUTPUT <br />
     Sorted Merged array:(C)<br />
                Anil<br />
                John<br />
                Sachin<br />
                Suman<br />
                Usha<br /><br />
     Sorted first array:(A)<br />
                Anil<br />
                Suman<br /><br />
     Sorted Second array:(B)<br />
                John<br />
                Sachin<br />
                Usha<br />
            </pre>
        </div>
        </li>
        <li><h4>Question 3</h4>
                            <div class="hide qdesc">
            <p>
                A new advanced Operating System, incorporating the latest hi-tech features has been designed by Opera Computer Systems.<br />
                The task of generating copy protection codes to prevent software piracy has been entrusted to the Security Department.<br />
                The Security Department has decided to have codes containing a jumbled combination of alternate uppercase letters of the
                alphabet starting from A upto K (namely among A,C,E,G,I,K). The code may or not be in the consecutive series of alphabets.<br />
                Each code should not exceed 6 characters and there should be no repetition of characters. If it exceeds 6 characters,
                display an appropriate error message.<br />
                Write a program to input a code and its length. At the first instance of an error display "Invalid" stating the
                appropriate reason. In case of no error, display the message "Valid".<br /><br />
                Test your program for the following data and some random data.
            </p>
            <p>Examples</p>
            <pre>
        SAMPLE DATA<br /><br />
        INPUT     N=4     ABCE<br />
        OUTPUT    Invalid Only alternate letters permitted!<br /><br />
        INPUT     N=4    AcIK<br />
        OUTPUT    Invalid! Only upper case letters permitted!<br /><br />
        INPUT    N=4    AAKE<br />
        OUTPUT    Invalid! Repetition of characters not permitted!<br /><br />
        INPUT    N=7<br />
        OUTPUT    eRROR! Length of string should not exceed 6 characters!<br /><br />
        INPUT    N=4    AEGIK<br />
        OUTPUT    Invalid! String length not the same as specified!<br /><br />
        INPUT    N=3    ACE<br />
        OUTPUT    Valid!<br /><br />
        INPUT    N=5    GEAIK<br />
        OUTPUT    Valid!<br />
            </pre>
        </div>
        </li>
        </ol>
    <?php
    $isc06=$this->url(array('controller'=>'isc', 'action'=>'solved-isc-computer-practical', 'year'=>'2006'),null, true);
    ?>
    <p><a href="<?php echo $isc06;?>">Solutions</a></p>
