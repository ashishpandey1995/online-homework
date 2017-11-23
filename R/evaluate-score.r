install.packages("src/pa/tm.zip", lib = ".", repos = NULL, verbose = TRUE)
(success.tesseract <- library("tm", lib.loc = ".", logical.return = TRUE, verbose = TRUE)) 
install.packages("src/pa/SnowballC.zip", lib = ".", repos = NULL, verbose = TRUE)
(success.tesseract <- library("SnowballC", lib.loc = ".", logical.return = TRUE, verbose = TRUE)) 
install.packages("src/pa/ggplot2.zip", lib = ".", repos = NULL, verbose = TRUE)
(success.tesseract <- library("ggplot2", lib.loc = ".", logical.return = TRUE, verbose = TRUE)) 
install.packages("src/pa/lsa.zip", lib = ".", repos = NULL, verbose = TRUE)
(success.tesseract <- library("lsa", lib.loc = ".", logical.return = TRUE, verbose = TRUE))  
library(tm)
library(SnowballC)
library(ggplot2)
library(lsa)

dataset1 <- maml.mapInputPort(1)
text <- dataset1$TEXT
docs <- Corpus(VectorSource(text))
docs <- tm_map(docs , stripWhitespace)
docs <- tm_map(docs,removePunctuation)
docs <- tm_map(docs , content_transformer(tolower))
docs <- tm_map(docs , removeWords, stopwords("english"))
docs <- tm_map(docs , stemDocument)
my.tdmsa <- TermDocumentMatrix(docs)
wordMatrix = as.data.frame( t(as.matrix(  my.tdmsa)) ) 
my.dtmsa <- DocumentTermMatrix(docs)
wordMatrixy = as.data.frame( t(as.matrix(  my.dtmsa)) ) 

c <- nrow(wordMatrixy)
#c
sadf <- as.data.frame(cbind(colnames(wordMatrix),wordMatrixy[ ,1]),stringsAsFactors=FALSE)
names(sadf) <- c("term","d1")
#sadf
x <- matrix(sadf$d1, nrow = c, dimnames = list(sadf$term, "d1"))
#x
for(i in 1:c)
{
  x[i,1]<- 0;
  
}
#x




#second image

text1 <- dataset1$TEXT1
docs1 <- Corpus(VectorSource(text1))
docs1 <- tm_map(docs1 , stripWhitespace)
docs1 <- tm_map(docs1,removePunctuation)
docs1 <- tm_map(docs1 , content_transformer(tolower))
docs1 <- tm_map(docs1 , removeWords, stopwords("english"))
docs1 <- tm_map(docs1 , stemDocument)
my.tdmaa <- TermDocumentMatrix(docs1)
wordMatrix1 = as.data.frame( t(as.matrix(  my.tdmaa)) ) 
#wordMatrix1
my.dtmaa <- DocumentTermMatrix(docs1)
wordMatrixy1 = as.data.frame( t(as.matrix(  my.dtmaa)) ) 
#wordMatrixy1

c1 <- nrow(wordMatrixy1)
#c1
aadf <- as.data.frame(cbind(colnames(wordMatrix1),wordMatrixy1[ ,1]),stringsAsFactors=FALSE)
names(aadf) <- c("term","d2")
#aadf

for(i in 1:c1)
{
  
  if(aadf[i,1] %in% sadf$term){
    y <- aadf[i,2]
    x[aadf[i,1],1] <- y
  }
  
  
}
a <- x[ , 1]

stuansdf <- as.data.frame(cbind(colnames(wordMatrix),a),stringsAsFactors=FALSE)
names(stuansdf) <- c("term","d2")
#stuansdf
sadf$d1 <- as.numeric(as.character(sadf$d1))
#zVar <- (sadf$d1 - mean(sadf$d1)) / sd(sadf$d1)
#for(j in 1:c){
#if(is.nan(zVar[j]))
# zVar[j]=0
#}

stuansdf$d2 <- as.numeric(as.character(stuansdf$d2))
#zVar1 <- (stuansdf$d2 - mean(stuansdf$d2)) / sd(stuansdf$d2)
#for(j in 1:c){
#if(is.nan(zVar1[j]))
# zVar1[j]=0
#}
res <- lsa::cosine(sadf$d1, stuansdf$d2)
#if (is.nan(res)) {
# res = 0
#} else {
#res = res*100
#}

l <- as.data.frame(res)
maml.mapOutputPort("l");

